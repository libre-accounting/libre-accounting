<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Common\CompanyBackup;
use App\Utilities\CompanyArchive\ArchiveReader;
use App\Utilities\CompanyArchive\CompanyImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Headless / large-data company restore. Creates a fresh company and restores
 * the archive into it, in-process (no HTTP time limit).
 */
class CompanyImport extends Command
{
    protected $signature = 'company:import {file : Path to the .zip backup} {--user= : User id to own the restored records (defaults to the first user)}';

    protected $description = 'Restore a company from a Libre Accounting backup archive into a new company';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("File not found: {$file}");

            return self::FAILURE;
        }

        $userId = (int) ($this->option('user') ?: optional(User::first())->id);

        if (! $userId) {
            $this->error('No user to own the restored records; pass --user.');

            return self::FAILURE;
        }

        $reader = new ArchiveReader();
        $reader->open($file);

        try {
            $this->validate($reader);
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Bare shell company (no company:seed — archive brings its own data).
        $company = $this->createShellCompany($userId);
        $company->makeCurrent();

        $backup = CompanyBackup::create([
            'company_id' => $company->id,
            'user_id'    => $userId,
            'type'       => CompanyBackup::TYPE_IMPORT,
            'status'     => CompanyBackup::STATUS_PENDING,
            'filename'   => basename($file),
            'created_by' => $userId,
        ]);

        $total = (int) array_sum($reader->manifest()['tables'] ?? []);
        $backup->markProcessing($total);
        $bar = $this->output->createProgressBar($total);

        $importer = new CompanyImporter($reader, $company->id, $userId, function (int $p) use ($bar) {
            $bar->setProgress($p);
        });

        try {
            DB::transaction(fn () => $importer->importData());
            $importer->restoreFiles();
        } catch (\Throwable $e) {
            $backup->markFailed($e->getMessage());
            Company::withoutGlobalScopes()->where('id', $company->id)->forceDelete();
            $reader->close();
            $this->error('Import failed: ' . $e->getMessage());

            return self::FAILURE;
        }

        $reader->close();
        $bar->finish();
        $this->newLine();

        $backup->markCompleted($importer->getReport());

        $this->info("Restored company {$company->id} from {$file}");

        return self::SUCCESS;
    }

    protected function validate(ArchiveReader $reader): void
    {
        $manifest = $reader->manifest();

        if (($manifest['format'] ?? null) !== 'libre-company-archive') {
            throw new RuntimeException('Not a Libre Accounting company archive.');
        }

        if ((int) ($manifest['schema_version'] ?? 0) > (int) config('company_backups.schema_version', 1)) {
            throw new RuntimeException('Archive was created by a newer version; upgrade this instance first.');
        }
    }

    protected function createShellCompany(int $userId): Company
    {
        return DB::transaction(function () use ($userId) {
            $company = Company::create([
                'domain'       => '',
                'enabled'      => 1,
                'created_from' => 'console',
                'created_by'   => $userId,
            ]);

            $company->makeCurrent();

            setting()->set([
                'company.name'     => trans('company_backups.restoring'),
                'default.currency' => 'USD',
                'default.locale'   => 'en-GB',
            ]);
            setting()->save();

            if ($user = User::find($userId)) {
                $user->companies()->attach($company->id);
            }

            return $company;
        });
    }
}
