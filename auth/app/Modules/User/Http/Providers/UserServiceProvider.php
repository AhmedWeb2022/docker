<?php

namespace App\Modules\User\Http\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/User/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/User/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/User/Http/Routes/Dashboard.php'));
       
    }
}
