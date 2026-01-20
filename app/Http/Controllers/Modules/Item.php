<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Jobs\Install\DisableModule;
use App\Jobs\Install\EnableModule;
use App\Jobs\Install\UninstallModule;
use App\Traits\Modules;

class Item extends Controller
{
    use Modules;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:update-modules-item')->only('enable', 'disable');
        $this->middleware('permission:delete-modules-item')->only('uninstall');
    }

    public function uninstall($alias)
    {
        try {
            $name = module($alias)->getName();

            $this->dispatch(new UninstallModule($alias, company_id()));

            flash(trans('modules.uninstalled', ['module' => $name]))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('apps.home.index');
    }

    public function enable($alias)
    {
        try {
            $name = module($alias)->getName();

            $this->dispatch(new EnableModule($alias, company_id()));

            flash(trans('modules.enabled', ['module' => $name]))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('apps.home.index');
    }

    public function disable($alias)
    {
        try {
            $name = module($alias)->getName();

            $this->dispatch(new DisableModule($alias, company_id()));

            flash(trans('modules.disabled', ['module' => $name]))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('apps.home.index');
    }
}
