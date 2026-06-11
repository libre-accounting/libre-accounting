<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Common\CompanyBackup;
use App\Models\Common\Media as MediaModel;
use App\Notifications\Common\ExportCompleted;
use App\Notifications\Common\ExportFailed;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use MediaUploader;
use Throwable;

/**
 * Chain tail of ExportCompany: promotes the temp .zip into a private Media
 * (tag "company-backup") attached to the user, records the media id on the
 * backup row, marks it completed and notifies the user with a download link.
 *
 * Mirrors CreateMediableForExport, adding the CompanyBackup bookkeeping.
 */
class CreateMediableForCompanyBackup extends JobShouldQueue
{
    protected int $user_id;

    protected int $backup_id;

    public function booted(...$arguments): void
    {
        [$this->user_id, $this->backup_id] = $arguments;

        $this->onQueue('jobs');
    }

    public function handle(): void
    {
        $backup = CompanyBackup::findOrFail($this->backup_id);
        $user = User::findOrFail($this->user_id);

        // Queue workers don't run IdentifyCompany middleware; bind the company
        // so media rows and the download route resolve the right company_id.
        Company::withoutGlobalScopes()->findOrFail($backup->company_id)->makeCurrent();

        try {
            $source = storage_path('app/temp/' . $backup->filename);

            $media = MediaUploader::makePrivate()
                ->beforeSave(function (MediaModel $media) {
                    $media->company_id = company_id();
                })
                ->fromSource($source)
                ->toDirectory($this->getMediaFolder('exports'))
                ->upload();

            File::delete($source);

            $user->attachMedia($media, 'company-backup');

            $backup->update(['media_id' => $media->id]);
            $backup->markCompleted($backup->report);
        } catch (Throwable $e) {
            $backup->markFailed($e->getMessage());

            $this->notifySafely($user, new ExportFailed($e->getMessage()));

            throw $e;
        }

        // The backup is complete and downloadable regardless of notification
        // delivery, so a broken mail transport must NOT fail or unmark it. Send
        // best-effort and only log delivery errors.
        $download_url = route('uploads.download', ['id' => $backup->media_id, 'company_id' => company_id()]);

        $this->notifySafely($user, new ExportCompleted(trans_choice('general.companies', 1), $backup->filename, $download_url));
    }

    protected function notifySafely(User $user, $notification): void
    {
        try {
            $user->notify($notification);
        } catch (Throwable $e) {
            Log::warning('Company backup notification could not be delivered: ' . $e->getMessage());
        }
    }
}
