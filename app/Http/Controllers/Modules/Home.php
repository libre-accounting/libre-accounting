<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Traits\Modules;

class Home extends Controller
{
    use Modules;

    /**
     * Display the locally bundled apps.
     *
     * @return Response
     */
    public function index()
    {
        $modules = collect(module()->all())->map(function ($module) {
            $alias = $module->get('alias');

            return (object) [
                'alias'         => $alias,
                'name'          => $module->getName(),
                'description'   => $module->get('description'),
                'version'       => $module->get('version'),
                'enabled'       => $this->moduleIsEnabled($alias),
            ];
        })->sortBy('name')->values();

        return view('modules.home.index', compact('modules'));
    }
}
