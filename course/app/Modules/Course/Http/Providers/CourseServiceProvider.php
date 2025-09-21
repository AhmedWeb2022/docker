<?php

namespace App\Modules\Course\Http\Providers;

use Illuminate\Support\ServiceProvider;

class CourseServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Course/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Course/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Course/Http/Routes/ApiConnect.php'));
        $this->loadRoutesFrom(app_path('Modules/Course/Http/Routes/Dashboard.php'));
        $this->loadRoutesFrom(app_path('Modules/Course/Http/Routes/Teacher.php'));
    }
}
