<?php

namespace App\Modules\Notification\Http\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Notification\Http\Observers\NotificationObserver;
use App\Modules\Notification\Infrastructure\Persistence\Models\Notification\Notification;

class NotificationServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Notification/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Notification/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Notification/Http/Routes/Dashboard.php'));
    }
}
