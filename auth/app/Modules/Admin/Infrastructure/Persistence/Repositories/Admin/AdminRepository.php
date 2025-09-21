<?php

namespace App\Modules\Admin\Infrastructure\Persistence\Repositories\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\ApiService\WhatsAppApiService;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Admin\Infrastructure\Persistence\Models\Admin\Admin;

class AdminRepository extends BaseRepositoryAbstract
{
    /**
     * @var Admin
     */
    protected  $admin;
    protected $whatsAppApiService;
    public function __construct()
    {
        $this->setModel(new Admin());
        $this->admin = Auth::user();
        $this->whatsAppApiService = new WhatsAppApiService();
    }

    public function login($loginDTO): Admin
    {
            $credentials = $loginDTO->toArray();
            $fieldType = array_key_first($credentials);
            $admin = $this->getWhere($fieldType, $credentials[$fieldType], 'first');

            if (!$admin || !Hash::check($loginDTO->password, $admin->password)) {
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $admin;

    }

    public function checkCredential($checkCredentialDTO): Admin
    {
        try {
            $credentials = $checkCredentialDTO->credential();
            $fieldType = array_key_first($credentials);
            // dd($fieldType);
            // dd($credentials[$fieldType]);
            $admin = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            if (!$admin) {
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $admin;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function logout($logoutDTO): bool
    {
        try {
            $admin_device = $this->admin->adminDevices()->where('device_token', $logoutDTO->deviceToken)->first();
            if ($admin_device) {
                $admin_device->delete();
            }
            $this->admin->tokens()->delete();
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function sendWhatsAppMessage($phone, $message)
    {
        try {
            $handeledPhone = handelPhone($phone);
            $response = $this->whatsAppApiService->sendMessage($handeledPhone['phone'], $message, 'generalauth', $handeledPhone['countryCode']);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
