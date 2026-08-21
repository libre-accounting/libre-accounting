<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as Provider;
use Livewire\Livewire;

class App extends Provider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.installed') && config('app.debug')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        if (! env_is_production()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Laravel db fix
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();

        $this->registerLivewireRoutes();

        Model::preventLazyLoading(config('app.eager_load'));

        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            if (config('logging.default') == 'sentry') {
                \Sentry\Laravel\Integration::lazyLoadingViolationReporter();
            } else {
                $class = get_class($model);
    
                report("Attempted to lazy load [{$relation}] on model [{$class}].");
            }
        });
    }

    /**
     * Livewire talks to the app on a company-scoped URL, like every other
     * request, so the company middleware can resolve the current company.
     * The segment is optional for pages rendered outside of a company.
     */
    protected function registerLivewireRoutes(): void
    {
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update/{company_id?}', $handle)
                ->middleware(['web', 'company.identify', 'bindings', 'read.only']);
        });

        // The auth and permission checks of the page a component was rendered
        // on are repeated on its updates.
        Livewire::addPersistentMiddleware([
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\LogoutIfUserDisabled::class,
            \Laratrust\Middleware\Permission::class,
            \Laratrust\Middleware\Role::class,
            \Laratrust\Middleware\Ability::class,
        ]);
    }
}
