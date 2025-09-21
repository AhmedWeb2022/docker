<?php

namespace App\Modules\Website\Http\Providers;

use Illuminate\Support\ServiceProvider;

class WebsiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->loadMigrationsFrom(app_path('Modules/Website/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Website/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Website/Http/Routes/Dashboard.php'));
    }
}
