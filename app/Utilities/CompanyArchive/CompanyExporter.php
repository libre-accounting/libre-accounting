<?php

namespace App\Utilities\CompanyArchive;

use App\Models\Common\Media;
use App\Traits\Uploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Serializes one company into a .zip archive: a manifest, one NDJSON file per
 * table (raw rows, original ids/FKs preserved) and the physical media files.
 *
 * Reads go through the raw query builder with soft-deleted rows included, so
 * the global company scope, model caching and mutators never interfere and the
 * snapshot is faithful (deleted_at round-trips).
 */
class CompanyExporter
{
    use Uploads;

    protected int $companyId;

    protected ArchiveWriter $writer;

    /** @var callable|null progress callback: fn(int $processed) */
    protected $onProgress;

    /** @var array<int, int> media ids present on a non-local disk (bytes not bundled) */
    protected array $unbundledMedia = [];

    /** @var array<int, int> media ids whose file was missing on disk */
    protected array $missingFiles = [];

    public function __construct(int $companyId, ArchiveWriter $writer, callable $onProgress = null)
    {
        $this->companyId = $companyId;
        $this->writer = $writer;
        $this->onProgress = $onProgress;
    }

    /**
     * Count rows + media files up front so the UI has a denominator.
     */
    public function total(): int
    {
        $total = 0;

        foreach (Schema::tables() as $table => $spec) {
            $total += $this->query($table)->count();
        }

        if (config('company_backups.include_files', true)) {
            $total += $this->query('media')->count();
        }

        return $total;
    }

    /**
     * Build the archive manifest from a run() result. Shared by the queued job
     * and the artisan command so the on-disk format has one definition.
     */
    public static function buildManifest(int $companyId, array $result): array
    {
        return [
            'format'         => 'libre-company-archive',
            'schema_version' => (int) config('company_backups.schema_version', 1),
            'source' => [
                'app_version'  => version('short'),
                'generated_at' => now()->toIso8601String(),
                'db_driver'    => \DB::getDriverName(),
                'table_prefix' => \DB::getTablePrefix(),
            ],
            'company' => [
                'original_id' => $companyId,
                'profile' => [
                    'name'     => setting('company.name'),
                    'email'    => setting('company.email'),
                    'currency' => setting('default.currency', 'USD'),
                    'locale'   => setting('default.locale', 'en-GB'),
                ],
            ],
            'tables' => $result['counts'],
            'files' => [
                'count'     => $result['counts']['media'] ?? 0,
                'unbundled' => $result['unbundled_media'],
                'missing'   => $result['missing_files'],
            ],
        ];
    }

    public function run(): array
    {
        $processed = 0;
        $counts = [];

        foreach (Schema::tables() as $table => $spec) {
            $rows = $this->query($table)->orderBy($this->keyColumn($spec))->cursor();

            $counts[$table] = $this->writer->writeTable($table, $this->tick($rows, $processed));
        }

        if (config('company_backups.include_files', true)) {
            $this->copyMediaFiles($processed);
        }

        return [
            'counts' => $counts,
            'processed' => $processed,
            'unbundled_media' => $this->unbundledMedia,
            'missing_files' => $this->missingFiles,
        ];
    }

    /**
     * Build the base query for a table, scoped to the source company. Pivots
     * without company_id (user_dashboards) are scoped via the company's rows in
     * the referenced table.
     */
    protected function query(string $table)
    {
        $spec = Schema::tables()[$table];

        if (! empty($spec['no_company'])) {
            // user_dashboards: keep only rows whose dashboard belongs to the company.
            $dashboardIds = DB::table('dashboards')->where('company_id', $this->companyId)->pluck('id');

            return DB::table($table)->whereIn('dashboard_id', $dashboardIds);
        }

        return DB::table($table)->where('company_id', $this->companyId);
    }

    protected function keyColumn(array $spec): string
    {
        // Keyless pivots have no id; order by a stable column instead.
        if (! empty($spec['pivot'])) {
            return ! empty($spec['no_company']) ? 'dashboard_id' : 'media_id';
        }

        return 'id';
    }

    /**
     * Wrap a cursor to increment the progress counter as rows stream past.
     */
    protected function tick(iterable $rows, int &$processed): iterable
    {
        foreach ($rows as $row) {
            $processed++;

            if ($this->onProgress && $processed % (int) config('company_backups.batch_size', 500) === 0) {
                ($this->onProgress)($processed);
            }

            yield (array) $row;
        }
    }

    protected function copyMediaFiles(int &$processed): void
    {
        Media::withoutGlobalScopes()
            ->where('company_id', $this->companyId)
            ->withTrashed()
            ->cursor()
            ->each(function (Media $media) use (&$processed) {
                $processed++;

                if ($this->onProgress && $processed % (int) config('company_backups.batch_size', 500) === 0) {
                    ($this->onProgress)($processed);
                }

                $driver = config('filesystems.disks.' . $media->disk . '.driver');

                // v1: bundle only local-disk bytes; remote (S3) rows are recorded
                // in media.ndjson but flagged for manual re-upload.
                if ($driver !== 'local') {
                    $this->unbundledMedia[] = $media->id;

                    return;
                }

                $disk = Storage::disk($media->disk);
                $path = $media->getDiskPath();

                if (! $disk->exists($path)) {
                    $this->missingFiles[] = $media->id;

                    return;
                }

                $entry = "files/{$media->id}/{$media->filename}.{$media->extension}";

                $this->writer->addFileStream($media->id, $disk->readStream($path), $entry);
            });
    }
}
