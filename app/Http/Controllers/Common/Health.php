<?php

namespace App\Http\Controllers\Common;

use Illuminate\Routing\Controller;

class Health extends Controller
{
    /**
     * Liveness/readiness probe endpoint. Must stay dependency-free: no
     * database, session, or auth, so it responds before the app is installed.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        return response()->json(['status' => 'ok']);
    }
}
