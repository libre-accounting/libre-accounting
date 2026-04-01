<?php

namespace App\Http\Controllers\Install;

use App\Http\Requests\Install\Database as Request;
use App\Utilities\Installer;
use Illuminate\Routing\Controller;

class Database extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('install.database.create', [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'host'       => env('DB_HOST'    , 'localhost'),
            'port'       => env('DB_PORT'    , '3306'),
            'username'   => env('DB_USERNAME', ''),
            'password'   => env('DB_PASSWORD', ''),
            'database'   => env('DB_DATABASE', ''),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $connection = $request->input('connection', 'mysql');

        // Make the chosen driver the active default so the config lookups below resolve.
        config(['database.default' => $connection]);

        $database = $request['database'];
        $prefix   = config("database.connections.$connection.prefix", null);

        if ($connection === 'sqlite') {
            // SQLite only needs a file path; host/port/credentials are irrelevant.
            $host = $port = $username = $password = null;

            if (empty($database)) {
                $database = database_path('database.sqlite');
            }
        } else {
            $host     = $request['hostname'];
            $port     = $request->input('port') ?: config("database.connections.$connection.port", '3306');
            $username = $request['username'];
            $password = $request['password'];
        }

        // Check database connection
        if (!Installer::createDbTables($host, $port, $database, $username, $password, $prefix, $connection)) {
            $response = [
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('install.error.connection'),
                'data' => null,
                'redirect' => null,
            ];
        }

        if (empty($response)) {
            $response['redirect'] = route('install.settings');
        }

        return response()->json($response);
    }
}
