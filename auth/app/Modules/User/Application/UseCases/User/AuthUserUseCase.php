<?php

namespace App\Modules\User\Application\UseCases\User;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Holders\UserHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\User\Application\DTOS\User\LogoutDTO;
use App\Modules\User\Http\Resources\User\UserResource;
use App\Modules\User\Application\DTOS\User\UserFilterDTO;
use App\Modules\User\Application\DTOS\User\UpdateAccountDTO;
use App\Modules\User\Application\DTOS\User\ChangePasswordDTO;
use App\Modules\User\Application\DTOS\User\CheckAuthenticationDTO;
use App\Modules\User\Application\DTOS\User\UserDTO;
use App\Modules\User\Infrastructure\Persistence\Repositories\User\UserRepository;

class AuthUserUseCase
{

    protected $userRepository;
    /**
     *  @var User
     */

    protected $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
    }



    public function logout(LogoutDTO $logoutDTO): DataStatus
    {
        try {
            $response = $this->userRepository->logout($logoutDTO);
            return DataSuccess(
                status: true,
                message: 'success',
                data: $response
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function changePassword(ChangePasswordDTO $changePasswordDTO): DataStatus
    {
        try {
            if (!Hash::check($changePasswordDTO->old_password, $this->user->password)) {
                return DataFailed(
                    status: false,
                    message: 'The old password is incorrect'
                );
            }
            if (Hash::check($changePasswordDTO->new_password, $this->user->password)) {
                return DataFailed(
                    status: false,
                    message: 'The new password cannot be the same as the old password'
                );
            }
            $response = $this->userRepository->update($this->user->id, $changePasswordDTO);
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

    public function updateAccount(UpdateAccountDTO $updateAccountDTO): DataStatus
    {
        try {
            // dd($updateAccountDTO);
            $user = $this->userRepository->update($this->user->id, $updateAccountDTO);
            if ($user) {
                $stage = ($updateAccountDTO->stage_id != null || $user->stage_id != null) ? $this->userRepository->getStage($this->user->id) : null;
                $location = ($updateAccountDTO->location_id != null || $user->location_id != null) ? $this->userRepository->getLocation($this->user->id) : null;
                $nationality = ($updateAccountDTO->nationality_id != null || $user->nationality_id != null) ? $this->userRepository->getNationality($this->user->id) : null;
                $user['stage'] = $stage != null  ? $stage['data'] : null;
                $user['location'] = $location != null ? $location['data'] : null;
                $user['nationality'] = $nationality != null ? $nationality['data'] : null;
            }
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

    public function deleteAccount(): DataStatus
    {
        try {
            $this->userRepository->delete($this->user->id);
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
                    message: 'Unauthinticated - Authentication required'
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

    public function updateImage(UserDTO $userDTO): DataStatus
    {
        try {
            $user = $this->userRepository->update($this->user->id, $userDTO);
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
}
