<?php

namespace App\Http\Livewire\Common;

use App\Models\Common\CompanyBackup;
use Livewire\Component;

class CompanyBackupProgress extends Component
{
    public CompanyBackup $backup;

    public function mount(CompanyBackup $backup): void
    {
        $this->backup = $backup;
    }

    /**
     * Re-read the row each poll so status/counter writes (in-request when sync,
     * cross-process when queued) surface without a page reload.
     */
    public function refreshStatus(): void
    {
        $this->backup->refresh();
    }

    public function getDownloadUrlProperty(): ?string
    {
        if (! $this->backup->media_id) {
            return null;
        }

        return route('uploads.download', [
            'id'         => $this->backup->media_id,
            'company_id' => $this->backup->company_id,
        ]);
    }

    public function getCompanyUrlProperty(): ?string
    {
        if (! $this->backup->company_id) {
            return null;
        }

        return route('companies.switch', $this->backup->company_id);
    }

    public function render()
    {
        return view('livewire.common.company-backup-progress');
    }
}
