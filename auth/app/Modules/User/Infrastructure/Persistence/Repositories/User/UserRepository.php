<?php

namespace App\Modules\User\Infrastructure\Persistence\Repositories\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Modules\Base\Domain\ApiService\WhatsAppApiService;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\User\Infrastructure\Persistence\Models\User\User;
use App\Modules\Base\Domain\Services\Notification\NotificationApiService;
use App\Modules\User\Infrastructure\Persistence\ApiService\StageApiService;
use App\Modules\User\Infrastructure\Persistence\ApiService\LocationApiService;

class UserRepository extends BaseRepositoryAbstract
{
    /**
     * @var User
     */
    protected  $user;
    protected $whatsAppApiService;
    protected $stageApiService;
    protected $locationApiService;
    protected $notificationApiService;
    public function __construct()
    {
        $this->setModel(new User());
        $this->user = Auth::user();
        $this->whatsAppApiService = new WhatsAppApiService();
        $this->stageApiService = new StageApiService();
        $this->locationApiService = new LocationApiService();
        $this->notificationApiService = new NotificationApiService();
    }

    public function login($loginDTO): User
    {
        try {
            // dd($loginDTO);
            $credentials = $loginDTO->toArray();
            $fieldType = array_key_first($credentials);
            $user = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            // dd(Hash::check($loginDTO->password, $user->password));
            if (!$user || !Hash::check($loginDTO->password, $user->password)) {
                // dd($user);
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function checkCredential($checkCredentialDTO): User
    {
        try {
            $credentials = $checkCredentialDTO->credential();
            $fieldType = array_key_first($credentials);
            // dd($fieldType);
            // dd($credentials[$fieldType]);
            $user = $this->getWhere($fieldType, $credentials[$fieldType], 'first');

            if (!$user) {
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function logout($logoutDTO): bool
    {
        try {
            $user_device = $this->user->userDevices()->where('device_token', $logoutDTO->deviceToken)->first();
            if ($user_device) {
                $user_device->delete();
            }
            $this->user->tokens()->delete();
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

    public function getStage($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $stage = $this->stageApiService->fetchStageDetails($user->stage_id);

            if (!$stage) {
                throw new \Exception('Stage not found.');
            }
            return $stage;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getLocation($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $location = $this->locationApiService->fetchLocationDetails($user->location_id);
            if (!$location) {
                throw new \Exception('Location not found.');
            }
            return $location;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function getNationality($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $location = $this->locationApiService->fetchLocationDetails($user->nationality_id);
            if (!$location) {
                throw new \Exception('Location not found.');
            }
            return $location;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function forceLogout($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->tokens()->delete();

            $this->handelNotification(user: $user, notification_type: 21, body: [
                'title' => 'Logout',
                'subtitle' => 'force logout',
                'body' => 'Your account has been logged out.',
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function blockUser($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->update(['is_blocked' => true]);

            $this->handelNotification(user: $user, notification_type: 2, body: [
                'title' => 'Blocked',
                'subtitle' => 'Blocked',
                'body' => 'Your account has been blocked.',
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function unblockUser($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->update(['is_blocked' => false]);

            $this->handelNotification(user: $user, notification_type: 22, body: [
                'title' => 'Unblocked',
                'subtitle' => 'Unblocked',
                'body' => 'Your account has been unblocked.',
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function checkAuthentication($dto)
    {
        try {
            $token = $dto->token;
            $accessToken = PersonalAccessToken::findToken($token);
            // dd($accessToken);
            if (!$accessToken) {
                return false;
            }

            $user = $accessToken->tokenable;


            if (!$user ||  !($user instanceof User)) {
                return false;
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    private function handelNotification($user, $notification_type, $body)
    {

        $this->notificationApiService->sendNotification([
            'title' => $body['title'],
            'subtitle' => $body['subtitle'],
            'body' => $body['body'],
            'type' => 6,
            'type_id' => $user->id,
            'notification_type' => $notification_type,
            'user_ids' => [$user->id],
        ]);
    }
}
