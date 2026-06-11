<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use App\Models\Common\Company;
use App\Models\Common\CompanyBackup;
use App\Utilities\CompanyArchive\ArchiveWriter;
use App\Utilities\CompanyArchive\CompanyExporter;
use App\Notifications\Common\ExportFailed;
use App\Models\Auth\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Builds a full-company backup archive into storage/app/temp. Chained into
 * CreateMediableForCompanyBackup, which turns the file into a downloadable
 * Media and notifies the user.
 *
 * Scalars only in the constructor (serializable across the queue).
 */
class ExportCompany extends JobShouldQueue
{
    protected int $company_id;

    protected int $user_id;

    protected int $backup_id;

    /** filename set on the backup row for the chained job to promote. */
    public string $file_name = '';

    public function booted(...$arguments): void
    {
        [$this->company_id, $this->user_id, $this->backup_id] = $arguments;

        $this->onQueue('exports');
    }

    public function handle(): void
    {
        $backup = CompanyBackup::findOrFail($this->backup_id);
        $company = Company::withoutGlobalScopes()->findOrFail($this->company_id);

        $company->makeCurrent();

        // Long-running in the sync path; the queue path is unbounded anyway.
        @set_time_limit(0);

        try {
            $writer = new ArchiveWriter();
            $writer->open($this->tempPath($company));

            $exporter = new CompanyExporter(
                $this->company_id,
                $writer,
                fn (int $processed) => $backup->update(['processed' => $processed])
            );

            $backup->markProcessing($exporter->total());

            $result = $exporter->run();

            $writer->writeManifest(CompanyExporter::buildManifest($company->id, $result));

            $path = $writer->close();

            $this->file_name = basename($path);

            $backup->update([
                'filename'  => $this->file_name,
                'processed' => $backup->total,
            ]);

            // Completion (status + notification) is finalized by the chained
            // CreateMediableForCompanyBackup once the file becomes a Media.
        } catch (Throwable $e) {
            $backup->markFailed($e->getMessage());

            // Best-effort: a broken mail transport must not mask the real error.
            try {
                User::find($this->user_id)?->notify(new ExportFailed($e->getMessage()));
            } catch (Throwable $notifyError) {
                Log::warning('Company backup notification could not be delivered: ' . $notifyError->getMessage());
            }

            throw $e;
        }
    }

    protected function tempPath(Company $company): string
    {
        $dir = storage_path('app/temp');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        return $dir . '/company-' . $company->id . '-backup-' . date('YmdHis') . '.zip';
    }
}
