<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Company as Request;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\ExportCompany;
use App\Jobs\Common\CreateMediableForCompanyBackup;
use App\Jobs\Common\ImportCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;
use App\Models\Common\CompanyBackup;
use App\Traits\Uploads;
use App\Traits\Users;
use Akaunting\Money\Currency as MoneyCurrency;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;

class Companies extends Controller
{
    use Uploads, Users;

    public function __construct()
    {
        // Add CRUD permission checks to all methods only remove index method for all companies list.
        $this->middleware('permission:create-common-companies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-common-companies')->only('show', 'edit', 'export');
        $this->middleware('permission:update-common-companies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-common-companies')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $companies = user()->companies()->collect();

        return $this->response('common.companies.index', compact('companies'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $money_currencies = MoneyCurrency::getCurrencies();

        $currencies = [];

        foreach ($money_currencies as $key => $item) {
            $currencies[$key] = $key . ' - ' . $item['name'];
        }

        return view('common.companies.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $current_company_id = company_id();

        $response = $this->ajaxDispatch(new CreateCompany($request));

        if ($response['success']) {
            $response['redirect'] = route('companies.switch', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.companies', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('companies.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        company($current_company_id)->makeCurrent();

        return response()->json($response);
    }

    /**
     * Export a full backup of the company (settings, all records and uploaded
     * files) as a downloadable archive. The archive is built by a queued job
     * and, on completion, delivered as a private download + notification.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function export(Company $company)
    {
        if ($this->isNotUserCompany($company->id)) {
            return redirect()->route('companies.index');
        }

        $backup = CompanyBackup::create([
            'company_id' => $company->id,
            'user_id'    => user_id(),
            'type'       => CompanyBackup::TYPE_EXPORT,
            'status'     => CompanyBackup::STATUS_PENDING,
            'created_by' => user_id(),
        ]);

        // The Jobs trait dispatch() honours should_queue(): queued when a worker
        // is configured, inline (sync) otherwise. The chain tail promotes the
        // archive to a download and notifies the user in both modes.
        $job = new ExportCompany($company->id, user_id(), $backup->id);
        $job->chain([
            new CreateMediableForCompanyBackup(user_id(), $backup->id),
        ]);

        $this->dispatch($job);

        flash(trans('company_backups.messages.export_started'))->success();

        return redirect()->route('company-backups.show', $backup->id);
    }

    /**
     * Restore a company from an uploaded Libre Accounting backup archive into a
     * brand-new company. Called from the "create company" / wizard flow.
     *
     * @param  HttpRequest  $request
     *
     * @return Response
     */
    public function import(HttpRequest $request)
    {
        $request->validate([
            'backup' => ['required', 'file', 'mimes:zip'],
        ]);

        // Stage the upload on a dedicated disk (not swept by storage-temp:clear)
        // and pass the PATH to the job — never the UploadedFile (unserializable).
        $disk = config('company_backups.staging_disk', 'local');
        $dir = config('company_backups.staging_path', 'backups/incoming');

        $path = $request->file('backup')->store($dir, $disk);

        // Create a bare shell company (no company:seed — the archive brings its
        // own currencies/categories/accounts/settings, seeding would collide).
        $company = $this->createShellCompany();

        $backup = CompanyBackup::create([
            'company_id' => $company->id,
            'user_id'    => user_id(),
            'type'       => CompanyBackup::TYPE_IMPORT,
            'status'     => CompanyBackup::STATUS_PENDING,
            'filename'   => basename($path),
            'created_by' => user_id(),
        ]);

        $this->dispatch(new ImportCompany($disk, $path, $company->id, user_id(), $backup->id));

        flash(trans('company_backups.messages.import_started'))->success();

        return redirect()->route('company-backups.show', $backup->id);
    }

    /**
     * Create an empty company row attached to the current user, with just the
     * minimum settings needed for it to be listable/switchable before restore.
     */
    protected function createShellCompany(): Company
    {
        $current_company_id = company_id();

        $company = DB::transaction(function () {
            $company = Company::create([
                'domain'       => '',
                'enabled'      => 1,
                'created_from' => source_name(),
                'created_by'   => user_id(),
            ]);

            $company->makeCurrent();

            // Minimal settings so the company appears/switches before the import
            // job overwrites them with the archived values.
            setting()->set([
                'company.name'    => trans('company_backups.restoring'),
                'default.currency' => 'USD',
                'default.locale'  => app()->getLocale(),
            ]);
            setting()->save();

            if ($user = user()) {
                $user->companies()->attach($company->id);
            }

            return $company;
        });

        if (! empty($current_company_id)) {
            company($current_company_id)->makeCurrent();
        }

        return $company;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function edit(Company $company)
    {
        if ($this->isNotUserCompany($company->id)) {
            return redirect()->route('companies.index');
        }

        $money_currencies = MoneyCurrency::getCurrencies();

        $currencies = [];

        foreach ($money_currencies as $key => $item) {
            $currencies[$key] = $key . ' - ' . $item['name'];
        }

        return view('common.companies.edit', compact('company', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Company $company
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Company $company, Request $request)
    {
        $current_company_id = company_id();

        $response = $this->ajaxDispatch(new UpdateCompany($company, $request, company_id()));

        if ($response['success']) {
            $response['redirect'] = route('companies.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('companies.edit', $company->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        company($current_company_id)->makeCurrent();

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Company $company
     *
     * @return Response
     */
    public function enable(Company $company)
    {
        $response = $this->ajaxDispatch(new UpdateCompany($company, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('general.companies', 1)]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Company $company
     *
     * @return Response
     */
    public function disable(Company $company)
    {
        $response = $this->ajaxDispatch(new UpdateCompany($company, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => trans_choice('general.companies', 1)]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company $company
     *
     * @return Response
     */
    public function destroy(Company $company)
    {
        $response = $this->ajaxDispatch(new DeleteCompany($company));

        $response['redirect'] = route('companies.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.companies', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Change the active company.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function switch(Company $company)
    {
        if ($this->isUserCompany($company->id)) {
            $old_company_id = company_id();

            $company->makeCurrent();

            session(['dashboard_id' => user()->dashboards()->enabled()->pluck('id')->first()]);

            event(new \App\Events\Common\CompanySwitched($company, $old_company_id));

            // Check wizard
            if (! setting('wizard.completed', false)) {
                return redirect()->route('wizard.edit', ['company_id' => $company->id]);
            }
        }

        return redirect()->route('dashboard', ['company_id' => $company->id]);
    }

    public function autocomplete()
    {
        $query = request('query');

        $autocomplete = Company::autocomplete([
            'name' => $query
        ]);

        $companies = $autocomplete->get()->sortBy('name')->pluck('name', 'id');

        return response()->json([
            'success' => true,
            'message' => 'Get all companies.',
            'errors' => [],
            'count' => $companies->count(),
            'data' => ($companies->count()) ? $companies : null,
        ]);
    }
}
