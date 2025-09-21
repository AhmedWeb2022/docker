<?php

namespace App\Modules\Employee\Http\Providers;

use Illuminate\Support\ServiceProvider;

class EmployeeServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Employee/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Employee/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Employee/Http/Routes/ApiConnect.php'));
        $this->loadRoutesFrom(app_path('Modules/Employee/Http/Routes/Dashboard.php'));
    }
}
