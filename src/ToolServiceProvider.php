<?php

namespace Devpartners\AuditableLog;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();

            // By default logged in users can audit and restore
            Gate::define('audit', function ($user, $resource) {
                return true;
            });
            Gate::define('audit_restore', function ($user, $resource) {
                return true;
            });
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('auditable-log', __DIR__ . '/../dist/js/tool.js');
            Nova::style('auditable-log', __DIR__ . '/../dist/css/tool.css');
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Register the tool's routes.
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/auditable-log')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
