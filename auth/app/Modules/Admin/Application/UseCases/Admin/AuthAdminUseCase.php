<?php

namespace App\Modules\Admin\Application\UseCases\Admin;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Holders\AdminHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Admin\Application\DTOS\Admin\LogoutDTO;
use App\Modules\Admin\Http\Resources\Admin\AdminResource;
use App\Modules\Admin\Application\DTOS\Admin\AdminFilterDTO;
use App\Modules\Admin\Application\DTOS\Admin\UpdateAccountDTO;
use App\Modules\Admin\Application\DTOS\Admin\ChangePasswordDTO;
use App\Modules\Admin\Infrastructure\Persistence\Repositories\Admin\AdminRepository;

class AuthAdminUseCase
{

    protected $adminRepository;
    /**
     *  @var Admin
     */

    protected $admin;


    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->admin = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::ADMIN->value);
    }



    public function logout(LogoutDTO $logoutDTO): DataStatus
    {
        try {
            $response = $this->adminRepository->logout($logoutDTO);
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
            if (!Hash::check($changePasswordDTO->old_password, $this->admin->password)) {
                return DataFailed(
                    status: false,
                    message: 'The old password is incorrect'
                );
            }

            $response = $this->adminRepository->update($this->admin->id, $changePasswordDTO);
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
            $response = $this->adminRepository->update($this->admin->id, $updateAccountDTO);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new AdminResource($response)
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
            $this->adminRepository->delete($this->admin->id);
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
}
