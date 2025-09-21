<?php

namespace App\Modules\Base\Domain\Holders;

use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Base\Domain\Enums\AppModeEnum;
use App\Modules\Base\Domain\Entity\EmployeeEntity;
use App\Modules\Base\Domain\Entity\AuthEntityAbstract;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;
use App\Modules\Base\Domain\Support\AuthGuardServiceResolver;
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
            // /** @var Model */
            // $auth = auth($guard)->user();
            // if ($auth) {
            //     return $auth->getAuthEntity();
            // }
            // Handle custom service-based guards
            $token = request()->bearerToken();
            // dd($token);
            if (!$token) {
                return null; // Or throw an exception if strict
            }


            $authService = AuthGuardServiceResolver::resolve($guard);
            // dd($authService);
            Log::info(['authService' => $authService]);
            if (!$authService instanceof AuthenticatesViaToken) {
                return null; // Or throw an exception
            }

            $authResult = $authService->checkAuth($token);
            // dd($authResult);
            if (isset($authResult['status']) && $authResult['status'] === true && isset($authResult['data'])) {

                return new Fluent($authResult['data']);
            } else {
                return null;
            }
        }



        return new Fluent([
            'id' => 1,
            'name' => 'test'
        ]);
    }

    public function getUser($guard = null, ?Request $request = null)
    {
        $app_mod = env('App_Mode');

        if ($app_mod === AppModeEnum::PRODUCTION->value && $guard !== null) {
            /** @var Model */
            $auth = auth($guard)->user();
            if ($auth) {
                return $auth->getAuthEntity();
            }
        }

        $request = $request ?? app(Request::class);

        return new Fluent([
            'id' => $request ? intval($request->header('userId')) : null,
            'name' => 'techlab',
            'class' => 'App\Modules\User\Infrastructure\Persistence\Models\User',
        ]);
    }
}
