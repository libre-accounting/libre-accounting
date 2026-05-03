<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Models\Common\CompanyBackup;

class CompanyBackups extends Controller
{
    public function __construct()
    {
        // A backup belongs to the initiating user and only exposes progress /
        // a download link, so gate it with the companies read permission.
        $this->middleware('permission:read-common-companies')->only('show');
    }

    /**
     * Show the progress / result page for a company export or import.
     *
     * @param  CompanyBackup  $company_backup
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(CompanyBackup $company_backup)
    {
        // Only the initiating user may view their backup.
        if ($company_backup->user_id !== user_id()) {
            return redirect()->route('companies.index');
        }

        return view('common.company-backups.show', ['backup' => $company_backup]);
    }
}
