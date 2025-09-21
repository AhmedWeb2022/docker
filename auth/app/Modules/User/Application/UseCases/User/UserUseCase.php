<?php

namespace App\Modules\User\Application\UseCases\User;




use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Modules\Base\Domain\Holders\UserHolder;
use App\Modules\User\Application\DTOS\User\UserDTO;
use App\Modules\User\Application\DTOS\User\LoginDTO;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\User\Http\Resources\User\UserResource;
use App\Modules\User\Application\DTOS\User\RegisterDTO;
use App\Modules\User\Application\DTOS\User\CheckCodeDTO;
use App\Modules\User\Application\DTOS\User\UserFilterDTO;
use App\Modules\User\Application\DTOS\User\ResetPasswordDTO;
use App\Modules\User\Domain\Services\Email\EmailNotification;
use App\Modules\User\Application\DTOS\User\CheckCredentialDTO;
use App\Modules\User\Application\DTOS\User\CheckAuthenticationDTO;
use App\Modules\User\Infrastructure\Persistence\Repositories\User\UserRepository;
use App\Modules\User\Infrastructure\Persistence\Repositories\UserDevice\UserDeviceRepository;

class UserUseCase
{

    protected $userRepository;
    protected $userDeviceRepository;
    /**
     *  @var User
     */

    protected $user;


    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userDeviceRepository = new UserDeviceRepository();
    }

    /**
     * Register a new user.
     *
     * @param RegisterDTO $registerDTO
     * @return DataStatus
     */
    public function register(RegisterDTO $registerDTO): DataStatus
    {
        try {
            // dd($registerDTO);
            $user = $this->userRepository->create($registerDTO);

            if ($user) {
                if ($registerDTO->UserDevice() != []) {
                    $user->userDevices()->create($registerDTO->UserDevice());
                }
                $user['api_token'] = $user->createToken('api_token')->plainTextToken;
                $credentials = $registerDTO->credential();
                // dd($credentials);
                $fieldType = checkCredentialType($credentials);
                // dd("otp_{$user->$fieldType}");
                try {
                    $user->sendOtp($fieldType, $this->userRepository);
                } catch (\Exception $e) {
                    // You can log this exception or handle it differently if needed
                    // Log::error("Failed to send OTP: " . $e->getMessage());
                }
                $stage = $registerDTO->stage_id != null ? $this->userRepository->getStage($user->id) : null;
                $location = $registerDTO->location_id != null ? $this->userRepository->getLocation($user->id) : null;
                $nationality = $registerDTO->nationality_id != null ? $this->userRepository->getNationality($user->id) : null;
                $user['stage'] = $stage != null  ? $stage['data'] : null;
                $user['location'] = $location != null ? $location['data'] : null;
                $user['nationality'] = $nationality != null ? $nationality['data'] : null;
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Log in a user.
     *
     * @param LoginDTO $loginDTO
     * @return DataStatus
     */
    public function login(LoginDTO $loginDTO): DataStatus
    {
        try {
            // dd($loginDTO);
            $user = $this->userRepository->login($loginDTO);
            // dd($user);
            if ($user) {
                // dd($user ,'login');
                if ($loginDTO->UserDevice() != []) {
                    $user->userDevices()->updateOrCreate(['device_token' => $loginDTO->device_token], $loginDTO->UserDevice());
                }

                $user['api_token'] = $user->createToken('api_token')->plainTextToken;
                $credentials = $loginDTO->credential();

                $fieldType = checkCredentialType($credentials);
                // if ($user->is_email_verified || $user->is_phone_verified) {
                //     $user->sendOtp($fieldType, $this->userRepository);
                // }
                $stage = $user->stage_id != null ? $this->userRepository->getStage($user->id) : null;
                $location = $user->location_id != null ? $this->userRepository->getLocation($user->id) : null;
                $nationality = $user->nationality_id != null ? $this->userRepository->getNationality($user->id) : null;
                $user['stage'] = $stage != null  ? $stage['data'] : null;
                $user['location'] = $location != null ? $location['data'] : null;
                $user['nationality'] = $nationality != null ? $nationality['data'] : null;
                // dd($user);
            }
            // dd(new UserResource($user));
            return DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Check user credentials and send OTP if valid.
     *
     * @param CheckCredentialDTO $checkCredentialDTO
     * @return DataStatus
     */
    public function checkCredential(CheckCredentialDTO $checkCredentialDTO): DataStatus
    {
        try {
            $user = $this->userRepository->checkCredential($checkCredentialDTO);

            if ($user) {
                $credentials = $checkCredentialDTO->credential();
                $fieldType = checkCredentialType($credentials);
                try {

                    $user->sendOtp($fieldType, $this->userRepository);
                } catch (\Exception $e) {
                }
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Verify the provided OTP code.
     *
     * @param CheckCodeDTO $checkCodeDTO
     * @return DataStatus
     */
    public function checkCode(CheckCodeDTO $checkCodeDTO): DataStatus
    {
        try {
            $user = $this->userRepository->checkCredential($checkCodeDTO);
            // dd($user);
            $credentials = $checkCodeDTO->credential();
            // dd($credentials);
            $fieldType = checkCredentialType($credentials);
            if ($fieldType == 'email') {
                $user->is_email_verified = true;
                $user->email_verified_at = now();
                $user->save();
            } else {
                $user->is_phone_verified = true;
                $user->phone_verified_at = now();
                $user->save();
            }
            $cachedOtp = getCache("otp_{$user->$fieldType}");

            if (!$cachedOtp || !checkOTP($checkCodeDTO->code, $cachedOtp)) {
                return DataFailed(
                    status: false,
                    message: 'The provided code is incorrect.'
                );
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Reset the user's password using the provided OTP code.
     *
     * @param ResetPasswordDTO $resetPasswordDTO
     * @return DataStatus
     */
    public function resetPassword(ResetPasswordDTO $resetPasswordDTO): DataStatus
    {
        try {
            $user = $this->userRepository->checkCredential($resetPasswordDTO);
            $credentials = $resetPasswordDTO->credential();
            $fieldType = checkCredentialType($credentials);
            $cachedOtp = getCache("otp_{$user->$fieldType}");
            if (!$cachedOtp || !checkOTP($resetPasswordDTO->code, $cachedOtp)) {
                return DataFailed(
                    status: false,
                    message: 'The provided code is incorrect.'
                );
            }
            $this->userRepository->update($user->id, $resetPasswordDTO);
            // Remove OTP from cache after successful reset
            forgetCache("otp_{$user->$fieldType}");
            return DataSuccess(
                status: true,
                message: 'success',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchUsers(UserFilterDTO $userFilterDTO): DataStatus
    {
        try {
            // dd($userFilterDTO);
            $users = $this->userRepository->filter(
                dto: $userFilterDTO,
                paginate: $userFilterDTO->paginate,
                limit: $userFilterDTO->limit
            );
            return DataSuccess(
                status: true,
                message: 'success',
                data: $userFilterDTO->paginate ?  UserResource::collection($users)->response()->getData(true) : UserResource::collection($users)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchUserDetails(UserDTO $userDTO): DataStatus
    {
        try {
            $user = $this->userRepository->getById($userDTO->user_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createUser(UserDTO $UserDTO): DataStatus
    {
        try {
            $user = $this->userRepository->create($UserDTO);
            // dd($user);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateUser(UserDTO $UserDTO): DataStatus
    {
        try {
            $user = $this->userRepository->update($UserDTO->user_id, $UserDTO);

            return DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteUser(UserDTO $UserDTO): DataStatus
    {
        try {
            $user = $this->userRepository->delete($UserDTO->user_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function forceLogout(UserDTO $UserDTO): DataStatus
    {
        try {
            $user = $this->userRepository->forceLogout($UserDTO->user_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function blockUser(UserDTO $UserDTO): DataStatus
    {
        try {
            // dd($UserDTO);
            $user = $this->userRepository->blockUser($UserDTO->user_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function unblockUser(UserDTO $UserDTO): DataStatus
    {
        try {
            $user = $this->userRepository->unblockUser($UserDTO->user_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function getStageUserIds(UserFilterDTO $userFilterDTO): DataStatus
    {
        try {
            $userIds = $this->userRepository->getWhere('stage_id', $userFilterDTO->stage_id)->pluck('id');
            if ($userIds->isEmpty()) {
                return DataFailed(
                    status: false,
                    message: 'No users found for the given stage ID.'
                );
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: $userIds
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function getUsersDeviceTokens(UserFilterDTO $userFilterDTO): DataStatus
    {
        try {
            $device_tokens = $this->userDeviceRepository->getWhereIn('user_id', $userFilterDTO->user_ids)->pluck('device_token');

            if ($device_tokens->isEmpty()) {
                return DataFailed(
                    status: false,
                    message: 'No users found for the given user IDs.'
                );
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: $device_tokens
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function getAllUserIds(): DataStatus
    {
        try {
            $userIds = $this->userRepository->getAll()->pluck('id');
            return DataSuccess(
                status: true,
                message: 'success',
                data: $userIds
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function getUserById(UserDTO $UserDTO)
    {
        try {

            $user = $this->userRepository->getById($UserDTO->user_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: $user
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function checkUserExists(UserDTO $UserDTO): DataStatus
    {
        try {
            $user = $this->userRepository->checkExist('id', $UserDTO->user_id);
            if ($user) {
                return DataSuccess(
                    status: true,
                    message: 'User exists',
                    data: true
                );
            } else {
                return DataFailed(
                    status: false,
                    message: 'User does not exist',
                );
            }
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function checkAuthentication(CheckAuthenticationDTO $dto): DataStatus
    {

        try {
            $dto->token = request()->bearerToken();

            $user = $this->userRepository->checkAuthentication($dto);
            // dd($user);
            // dd($isAuth);
            if ($user == null) {
                return DataFailed(
                    status: false,
                    message: 'Unauthinticated - Authentication required',
                    statusCode: 200
                );
            }
            // $user = $this->userRepository->get($this->user->id);
            return DataSuccess(
                status: true,
                message: 'user found',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
