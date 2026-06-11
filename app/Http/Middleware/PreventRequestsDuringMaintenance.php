<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * The health endpoint must keep answering 200 during "artisan down",
     * otherwise orchestrator liveness probes restart the container and end
     * maintenance mode prematurely.
     *
     * @var array<int, string>
     */
    protected $except = [
        'health',
    ];
}
