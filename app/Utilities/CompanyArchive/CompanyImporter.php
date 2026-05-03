<?php

namespace App\Utilities\CompanyArchive;

use App\Traits\Uploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Restores a company archive into a freshly-created (empty) target company.
 *
 * The DB phase runs inside a transaction the caller opens: rows are inserted in
 * FK-safe order building an old->new IdMap, then self-refs / morphs / JSON id
 * lists are patched in a second pass. Physical files are restored AFTER commit
 * (the filesystem is not transactional) and the media rows' directory patched.
 */
class CompanyImporter
{
    use Uploads;

    protected ArchiveReader $reader;

    protected int $companyId;

    protected int $userId;

    protected IdMap $idMap;

    /** @var callable|null fn(int $processed) */
    protected $onProgress;

    /** @var array<string, string> valid currency codes in the new company */
    protected array $currencyCodes = [];

    protected string $fallbackCurrency = 'USD';

    /** @var array report of warnings surfaced to the user */
    protected array $report = [
        'skipped_reports' => [],
        'disabled_modules' => [],
        'missing_currency_refs' => 0,
        'unbundled_media' => [],
        'restored_files' => 0,
    ];

    protected int $processed = 0;

    public function __construct(ArchiveReader $reader, int $companyId, int $userId, callable $onProgress = null)
    {
        $this->reader = $reader;
        $this->companyId = $companyId;
        $this->userId = $userId;
        $this->onProgress = $onProgress;
        $this->idMap = new IdMap();
    }

    /**
     * DB phase — call inside a DB::transaction. Inserts all rows + patches.
     */
    public function importData(): void
    {
        $this->fallbackCurrency = $this->reader->manifest()['company']['profile']['currency'] ?? 'USD';

        foreach (Schema::tables() as $table => $spec) {
            $this->importTable($table, $spec);
        }

        $this->patchPhaseTwo();
        $this->resetPostgresSequences();
    }

    /**
     * File phase — call AFTER the transaction commits.
     */
    public function restoreFiles(): void
    {
        if (! config('company_backups.include_files', true)) {
            return;
        }

        $unbundled = array_flip($this->reader->manifest()['files']['unbundled'] ?? []);

        foreach ($this->reader->table('media') as $row) {
            $oldId = $row['id'];
            $newId = $this->idMap->get('media', $oldId);

            if (! $newId) {
                continue;
            }

            if (isset($unbundled[$oldId])) {
                $this->report['unbundled_media'][] = $newId;

                continue;
            }

            $entry = "{$row['filename']}.{$row['extension']}";
            $stream = $this->reader->fileStream((int) $oldId, $entry);

            if ($stream === null) {
                continue;
            }

            $folder = $this->folderLeaf($row['directory'] ?? 'settings');
            $newDir = $this->getMediaFolder($folder, $this->companyId);
            $disk = config('mediable.default_disk', 'uploads');

            Storage::disk($disk)->writeStream("{$newDir}/{$entry}", $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }

            DB::table('media')->where('id', $newId)->update([
                'directory' => $newDir,
                'disk'      => $disk,
            ]);

            $this->report['restored_files']++;
        }
    }

    public function getReport(): array
    {
        return $this->report;
    }

    public function getIdMap(): IdMap
    {
        return $this->idMap;
    }

    // -----------------------------------------------------------------

    protected function importTable(string $table, array $spec): void
    {
        // Cache valid currency codes for CODE:currency validation.
        if ($table === 'currencies') {
            // populated as rows are inserted below
        }

        $isReports = $table === 'reports';
        $isSettings = $table === 'settings';
        $isPivot = ! empty($spec['pivot']);
        $noCompany = ! empty($spec['no_company']);

        foreach ($this->reader->table($table) as $row) {
            $oldId = $row['id'] ?? null;

            // reports: skip rows whose class isn't installed on this instance.
            if ($isReports && ! empty($row['class']) && ! class_exists($row['class'])) {
                $this->report['skipped_reports'][] = $row['class'];

                continue;
            }

            $row = $this->prepareRow($table, $spec, $row, $noCompany, $isSettings);

            if ($row === null) {
                continue; // e.g. user_dashboards handled specially / dropped
            }

            if ($isPivot) {
                DB::table($table)->insert($row);
            } else {
                $newId = DB::table($table)->insertGetId($row);

                if ($oldId !== null) {
                    $this->idMap->set($table, $oldId, $newId);
                }
            }

            // Track currency codes as they land so later CODE:currency refs validate.
            if ($table === 'currencies' && ! empty($row['code'])) {
                $this->currencyCodes[$row['code']] = $row['code'];
            }

            $this->tick();
        }

        // modules: disable aliases whose code isn't installed here.
        if ($table === 'modules') {
            $this->disableUninstalledModules();
        }
    }

    /**
     * Normalize a row for insert: strip id, force company/owner, remap simple
     * FKs + morphs, null out foreign-user refs, drop columns the table lacks.
     * Self-refs / json ids are left at OLD values for the phase-2 patch.
     */
    protected function prepareRow(string $table, array $spec, array $row, bool $noCompany, bool $isSettings): ?array
    {
        unset($row['id']);

        // user_dashboards: source users don't exist here — link every restored
        // dashboard to the importing user instead, one row per dashboard.
        if ($table === 'user_dashboards') {
            $newDashboard = $this->idMap->get('dashboards', $row['dashboard_id']);

            if (! $newDashboard) {
                return null;
            }

            return ['user_id' => $this->userId, 'dashboard_id' => $newDashboard];
        }

        if (! $noCompany) {
            $row['company_id'] = $this->companyId;
        }

        // Owner => importing user (settings has no created_by column).
        if (! $isSettings && array_key_exists('created_by', $row)) {
            $row['created_by'] = $this->userId;
        }

        // Foreign users that are NOT created_by (contacts.user_id): null them.
        foreach ($spec['user_refs'] ?? [] as $col) {
            $row[$col] = null;
        }

        // Simple FKs to already-inserted rows.
        foreach ($spec['fks'] ?? [] as $col => $refTable) {
            if (! empty($row[$col])) {
                $row[$col] = $this->idMap->get($refTable, $row[$col], $row[$col]);
            }
        }

        // Morphs: remap id only when the type is one we imported.
        foreach ($spec['morphs'] ?? [] as $col => $m) {
            $type = $row[$m['type']] ?? null;
            $refTable = $m['map'][$type] ?? null;

            if ($refTable === 'companies') {
                // mediables attached to the company itself.
                $row[$col] = $this->companyId;
            } elseif ($refTable && ! empty($row[$col])) {
                $row[$col] = $this->idMap->get($refTable, $row[$col], $row[$col]);
            }
        }

        // CODE:currency validation on known columns.
        if (array_key_exists('currency_code', $row) && ! empty($row['currency_code'])) {
            if (! isset($this->currencyCodes[$row['currency_code']])) {
                $this->report['missing_currency_refs']++;
                $row['currency_code'] = $this->fallbackCurrency;
            }
        }

        return $row;
    }

    /**
     * Second pass: patch self-references, split_id, morph ids left at old
     * values, and JSON id lists, now that every table is fully inserted.
     */
    protected function patchPhaseTwo(): void
    {
        foreach (Schema::tables() as $table => $spec) {
            $hasSelf = ! empty($spec['self_refs']);
            $hasJson = ! empty($spec['json_ids']);

            if (! $hasSelf && ! $hasJson) {
                continue;
            }

            $zeroIsNull = array_flip($spec['zero_is_null'] ?? []);

            foreach ($this->reader->table($table) as $row) {
                $oldId = $row['id'] ?? null;
                $newId = $oldId !== null ? $this->idMap->get($table, $oldId) : null;

                if (! $newId) {
                    continue;
                }

                $update = [];

                foreach ($spec['self_refs'] ?? [] as $col => $refTable) {
                    $val = $row[$col] ?? null;

                    if (isset($zeroIsNull[$col]) && (int) $val === 0) {
                        continue; // 0 means "no parent" — leave the default
                    }

                    if (! empty($val)) {
                        $update[$col] = $this->idMap->get($refTable, $val, $val);
                    }
                }

                foreach ($spec['json_ids'] ?? [] as $col => $refTable) {
                    $ids = json_decode($row[$col] ?? '[]', true) ?: [];
                    $new = array_values(array_filter(array_map(
                        fn ($id) => $this->idMap->get($refTable, $id),
                        $ids
                    )));
                    $update[$col] = json_encode($new);
                }

                if (! empty($update)) {
                    DB::table($table)->where('id', $newId)->update($update);
                }
            }
        }

        $this->remapSettingIds();
    }

    /**
     * Rewrite setting values that hold ids (company.logo, default.*_category,
     * default.account). default.currency stays (it's a code).
     */
    protected function remapSettingIds(): void
    {
        foreach (Schema::SETTING_ID_KEYS as $key => $refTable) {
            $row = DB::table('settings')
                ->where('company_id', $this->companyId)
                ->where('key', $key)
                ->first();

            if (! $row || ! is_numeric($row->value)) {
                continue;
            }

            $new = $this->idMap->get($refTable, (int) $row->value);

            if ($new) {
                DB::table('settings')->where('id', $row->id)->update(['value' => $new]);
            }
        }
    }

    protected function disableUninstalledModules(): void
    {
        $rows = DB::table('modules')->where('company_id', $this->companyId)->get();

        foreach ($rows as $row) {
            if (! $this->moduleInstalled($row->alias)) {
                DB::table('modules')->where('id', $row->id)->update(['enabled' => 0]);
                $this->report['disabled_modules'][] = $row->alias;
            }
        }
    }

    protected function moduleInstalled(string $alias): bool
    {
        try {
            return module($alias) !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * PostgreSQL doesn't advance a serial's sequence on explicit-id insert, so
     * the next natural insert would collide. Reset each table's sequence.
     * No-op on mysql/sqlite/sqlsrv.
     */
    protected function resetPostgresSequences(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $prefix = DB::getTablePrefix();

        foreach (Schema::tablesWithSerialId() as $table) {
            $qualified = $prefix . $table;

            DB::statement(
                "SELECT setval(pg_get_serial_sequence(?, 'id'), COALESCE((SELECT MAX(id) FROM {$qualified}), 1))",
                [$qualified]
            );
        }
    }

    protected function tick(): void
    {
        $this->processed++;

        if ($this->onProgress && $this->processed % (int) config('company_backups.batch_size', 500) === 0) {
            ($this->onProgress)($this->processed);
        }
    }

    /**
     * Extract the trailing folder name from a stored media directory
     * ("2021/04/09/34235/invoices" -> "invoices").
     */
    protected function folderLeaf(string $directory): string
    {
        $parts = explode('/', trim($directory, '/'));

        return end($parts) ?: 'settings';
    }
}
