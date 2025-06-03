<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // force https if available
        if (App::environment(['staging', 'production'])) {
            URL::forceScheme('https');

            // auto APP_URL from domain
            if (!app()->runningInConsole() && !env('APP_URL')) {
                $host = request()->getSchemeAndHttpHost();

                config(['app.url' => $host]);
                config(['sanctum.stateful' => [parse_url($host, PHP_URL_HOST)]]);
            }
        }

        // fix deprecated warning
        error_reporting(E_ALL & ~E_DEPRECATED);

        // laravel 12.x
        Model::automaticallyEagerLoadRelationships();
    }
}
