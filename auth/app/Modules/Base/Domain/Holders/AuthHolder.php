<?php

namespace App\Modules\Base\Domain\Holders;

use Illuminate\Support\Fluent;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Base\Domain\Enums\AppModeEnum;
use App\Modules\Base\Domain\Entity\EmployeeEntity;
use App\Modules\Base\Domain\Entity\AuthEntityAbstract;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;

class AuthHolder
{
    public Model $auth;
    private static ?self $instance = null;
    private function __construct() {}


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAuth($guard = null)
    {
        $app_mod = env('App_Mode');

        if ($app_mod === AppModeEnum::PRODUCTION->value && $guard !== null) {
            /** @var Model */
            $auth = auth($guard)->user();
            if ($auth) {
                return $auth->getAuthEntity();
            }
        }

        // For TEST and fallback to dummy object
        return new Fluent([
            'id' => 1,
            'name' => 'test'
        ]);
    }
}
