<?php

namespace App\Modules\Base\Http\Providers;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->loadMigrationsFrom(app_path('Modules/Base/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Base/Http/Routes/Api.php'));
        foreach (glob(app_path('Modules/Base/Application/Helpers') . '/*.php') as $filename) {
            require_once $filename;
        }

        // Register middleware
        $this->app['router']->aliasMiddleware('baseAuthMiddleware', \App\Modules\Base\Http\Middleware\BaseAuthMiddleware::class);
        // config([
        //     'auth.guards.user' => [
        //         'driver' => 'sanctum',
        //         'provider' => 'employees',
        //     ],
        //     'auth.providers.employees' => [
        //         'driver' => 'eloquent',
        //         'model' => User::class,
        //     ],
        // ]);
    }
}
