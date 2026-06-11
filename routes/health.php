<?php

use Illuminate\Support\Facades\Route;

/**
 * No middleware applied to these routes
 *
 * @see \App\Providers\Route::mapHealthRoutes
 */

Route::get('health', 'Common\Health')->name('health');
