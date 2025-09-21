<?php

namespace App\Modules\Diploma\Http\Providers;

use Illuminate\Support\ServiceProvider;

class DiplomaServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Diploma/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Diploma/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Diploma/Http/Routes/Dashboard.php'));
    }
}
