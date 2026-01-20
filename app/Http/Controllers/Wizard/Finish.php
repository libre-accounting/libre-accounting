<?php

namespace App\Http\Controllers\Wizard;

use App\Abstracts\Http\Controller;

class Finish extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-admin-panel')->only('index', 'show', 'edit', 'export');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        setting()->set('wizard.completed', 1);

        // Save all settings
        setting()->save();

        return $this->response('wizard.finish.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update()
    {
        setting()->set('wizard.completed', 1);

        // Save all settings
        setting()->save();

        return response()->json([]);
    }
}
