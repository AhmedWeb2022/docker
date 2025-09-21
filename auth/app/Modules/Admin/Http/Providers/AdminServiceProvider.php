<?php

namespace App\Modules\Admin\Http\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Admin/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Admin/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Admin/Http/Routes/Dashboard.php'));

    }
}
