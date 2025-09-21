<?php

namespace App\Modules\Admin\Application\UseCases\Admin;




use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Modules\Admin\Application\DTOS\Admin\LoginDTO;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Admin\Http\Resources\Admin\AdminResource;
use App\Modules\Admin\Application\DTOS\Admin\RegisterDTO;
use App\Modules\Admin\Application\DTOS\Admin\CheckCodeDTO;
use App\Modules\Admin\Application\DTOS\Admin\ResetPasswordDTO;
use App\Modules\Base\Domain\Services\Email\EmailNotification;
use App\Modules\Admin\Application\DTOS\Admin\CheckCredentialDTO;
use App\Modules\Admin\Application\DTOS\Admin\AdminDTO;
use App\Modules\Admin\Application\DTOS\Admin\AdminFilterDTO;
use App\Modules\Admin\Infrastructure\Persistence\Repositories\Admin\AdminRepository;
use App\Modules\Base\Domain\Holders\AdminHolder;

class AdminUseCase
{

    protected $adminRepository;
    /**
     *  @var Admin
     */

    protected $admin;


    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * Register a new Admin.
     *
     * @param RegisterDTO $registerDTO
     * @return DataStatus
     */
    public function register(RegisterDTO $registerDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->create($registerDTO);
            if ($admin) {
                if ($registerDTO->adminDevice() != []) {
                    $admin->adminDevices()->create($registerDTO->adminDevice());
                }
                $admin['api_token'] = $admin->createToken('api_token')->plainTextToken;
                $credentials = $registerDTO->credential();
                $fieldType = checkCredentialType($credentials);
                if ($admin->is_email_verified || $admin->is_phone_verified) {
                    $admin->sendOtp($fieldType, $this->adminRepository);
                }
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: new AdminResource($admin)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Log in a Admin.
     *
     * @param LoginDTO $loginDTO
     * @return DataStatus
     */
    public function login(LoginDTO $loginDTO): DataStatus
    {
        $admin = $this->adminRepository->login($loginDTO);
        if ($admin) {

            if ($loginDTO->adminDevice() != []) {
                $admin->adminDevices()->updateOrCreate(['device_token' => $loginDTO->device_token], $loginDTO->adminDevice());
            }
            $admin['api_token'] = $admin->createToken('api_token')->plainTextToken;
            $credentials = $loginDTO->credential();
            $fieldType = checkCredentialType($credentials);
            if ($admin->is_email_verified || $admin->is_phone_verified) {
                $admin->sendOtp($fieldType, $this->adminRepository);
            }
        }
        return DataSuccess(
            status: true,
            message: 'success',
            data: new AdminResource($admin)
        );
    }

    /**
     * Check Admin credentials and send OTP if valid.
     *
     * @param CheckCredentialDTO $checkCredentialDTO
     * @return DataStatus
     */
    public function checkCredential(CheckCredentialDTO $checkCredentialDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->checkCredential($checkCredentialDTO);
            if ($admin) {
                $otp = generateOTP();
                $credentials = $checkCredentialDTO->credential();
                $fieldType = checkCredentialType($credentials);
                $admin->sendOtp($fieldType, $this->adminRepository);
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
            $admin = $this->adminRepository->checkCredential($checkCodeDTO);

            $credentials = $checkCodeDTO->credential();
            $fieldType = checkCredentialType($credentials);
            $cachedOtp = getCache("otp_{$admin->$fieldType}");

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
     * Reset the Admin's password using the provided OTP code.
     *
     * @param ResetPasswordDTO $resetPasswordDTO
     * @return DataStatus
     */
    public function resetPassword(ResetPasswordDTO $resetPasswordDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->checkCredential($resetPasswordDTO);
            $credentials = $resetPasswordDTO->credential();
            $fieldType = checkCredentialType($credentials);
            $cachedOtp = getCache("otp_{$admin->$fieldType}");
            if (!$cachedOtp || !checkOTP($resetPasswordDTO->code, $cachedOtp)) {
                return DataFailed(
                    status: false,
                    message: 'The provided code is incorrect.'
                );
            }
            $this->adminRepository->update($admin->id, $resetPasswordDTO);
            // Remove OTP from cache after successful reset
            forgetCache("otp_{$admin->$fieldType}");
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

    public function fetchAdmins(AdminFilterDTO $adminFilterDTO): DataStatus
    {
        try {
            // dd($adminFilterDTO);
            $admins = $this->adminRepository->filter(
                dto: $adminFilterDTO,
                paginate: $adminFilterDTO->paginate,
                limit: $adminFilterDTO->limit,
            );
            return DataSuccess(
                status: true,
                message: 'success',
                data: AdminResource::collection($admins)->response()->getData(true)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchAdminDetails(AdminDTO $adminDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->getById($adminDTO->admin_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new AdminResource($admin)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createAdmin(AdminDTO $adminDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->create($adminDTO);
            // dd($admin);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new AdminResource($admin)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateAdmin(AdminDTO $adminDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->update($adminDTO->admin_id, $adminDTO);

            return DataSuccess(
                status: true,
                message: 'success',
                data: new AdminResource($admin)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteAdmin(AdminDTO $adminDTO): DataStatus
    {
        try {
            $admin = $this->adminRepository->delete($adminDTO->admin_id);
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
}
