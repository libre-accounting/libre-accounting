<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Common\CompanyBackup;
use App\Notifications\Common\ImportCompleted;
use App\Notifications\Common\ImportFailed;
use App\Utilities\CompanyArchive\ArchiveReader;
use App\Utilities\CompanyArchive\CompanyImporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

/**
 * Restores a company archive into a freshly-created target company.
 *
 * The archive is staged on a disk by the controller and passed by path — never
 * an UploadedFile (not serializable). The DB restore runs in one transaction;
 * files are restored after commit. On any failure the shell company is removed
 * so no half-restored company lingers.
 */
class ImportCompany extends JobShouldQueue
{
    protected string $disk;

    protected string $path;

    protected int $company_id;

    protected int $user_id;

    protected int $backup_id;

    public function booted(...$arguments): void
    {
        [$this->disk, $this->path, $this->company_id, $this->user_id, $this->backup_id] = $arguments;

        $this->onQueue('jobs');
    }

    public function handle(): void
    {
        $backup = CompanyBackup::findOrFail($this->backup_id);
        $user = User::findOrFail($this->user_id);
        $company = Company::withoutGlobalScopes()->findOrFail($this->company_id);

        $company->makeCurrent();

        @set_time_limit(0);

        $localZip = null;

        try {
            $localZip = $this->localize();

            $reader = new ArchiveReader();
            $reader->open($localZip);

            $this->validate($reader);

            $backup->markProcessing($this->manifestTotal($reader));

            $importer = new CompanyImporter(
                $reader,
                $this->company_id,
                $this->user_id,
                fn (int $processed) => $backup->update(['processed' => $processed])
            );

            DB::transaction(function () use ($importer) {
                $importer->importData();
            });

            // Files after commit (filesystem is not transactional).
            $importer->restoreFiles();

            $reader->close();

            // Raw inserts bypass the model cache; flush so lists aren't stale.
            $this->flushCompanyCache($company);

            $backup->markCompleted($importer->getReport());
        } catch (Throwable $e) {
            $backup->markFailed($e->getMessage());

            // Remove the half-restored shell company (its rows roll back with the
            // transaction; delete the shell row + any files already written).
            $this->rollbackCompany($company);

            $this->notifySafely($user, new ImportFailed($e->getMessage()));

            throw $e;
        } finally {
            // Drop the staged upload and any localized copy.
            Storage::disk($this->disk)->delete($this->path);

            if ($localZip && File::exists($localZip)) {
                File::delete($localZip);
            }
        }

        // The company is fully restored regardless of notification delivery, so
        // a broken mail transport must NOT throw here — a notify failure inside
        // the try block above would trigger rollbackCompany() and destroy a
        // perfectly good import. Best-effort only.
        $this->notifySafely($user, new ImportCompleted(trans_choice('general.companies', 1), $backup->total));
    }

    protected function notifySafely(User $user, $notification): void
    {
        try {
            $user->notify($notification);
        } catch (Throwable $e) {
            Log::warning('Company backup notification could not be delivered: ' . $e->getMessage());
        }
    }

    /**
     * Ensure the archive is a local filesystem path for ZipArchive (staging
     * disk may be remote). Returns a local path.
     */
    protected function localize(): string
    {
        $driver = config('filesystems.disks.' . $this->disk . '.driver');

        if ($driver === 'local') {
            return Storage::disk($this->disk)->path($this->path);
        }

        $dir = storage_path('app/temp');
        File::ensureDirectoryExists($dir);

        $local = $dir . '/' . basename($this->path);
        File::put($local, Storage::disk($this->disk)->get($this->path));

        return $local;
    }

    protected function validate(ArchiveReader $reader): void
    {
        $manifest = $reader->manifest();

        if (($manifest['format'] ?? null) !== 'libre-company-archive') {
            throw new RuntimeException(trans('company_backups.errors.invalid_format'));
        }

        $supported = (int) config('company_backups.schema_version', 1);

        if ((int) ($manifest['schema_version'] ?? 0) > $supported) {
            throw new RuntimeException(trans('company_backups.errors.newer_version'));
        }
    }

    protected function manifestTotal(ArchiveReader $reader): int
    {
        $manifest = $reader->manifest();

        $rows = array_sum($manifest['tables'] ?? []);

        return (int) $rows;
    }

    protected function flushCompanyCache(Company $company): void
    {
        // Raw DB::table() inserts bypass the GeneaLabs model cache, so cached
        // "empty" query results for the new company could linger. A full flush
        // is safe here: importing a whole company is rare and heavy.
        try {
            app('cache')->flush();
        } catch (Throwable $e) {
            // Best effort — never fail an otherwise-successful import on cache.
        }
    }

    protected function rollbackCompany(Company $company): void
    {
        try {
            DB::table('company_users')->where('company_id', $company->id)->delete();
        } catch (Throwable $e) {
            // pivot name differs / already gone
        }

        try {
            DB::table('user_companies')->where('company_id', $company->id)->delete();
        } catch (Throwable $e) {
        }

        // Hard-delete the shell (its child rows were rolled back with the txn).
        Company::withoutGlobalScopes()->where('id', $company->id)->forceDelete();
    }
}
