<?php

namespace App\Modules\Employee\Application\UseCases\Employee;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\EmployeeHolder;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Employee\Application\DTOS\Employee\LogoutDTO;
use App\Modules\Employee\Http\Resources\Employee\EmployeeResource;
use App\Modules\Employee\Application\DTOS\Employee\UpdateAccountDTO;
use App\Modules\Employee\Application\DTOS\Employee\ChangePasswordDTO;
use App\Modules\Employee\Application\DTOS\Employee\EmployeeFilterDTO;
use App\Modules\Employee\Http\Resources\Employee\ApiEmployeeResource;
use App\Modules\Employee\Application\DTOS\Employee\CheckAuthenticationDTO;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;
use App\Modules\Employee\Infrastructure\Persistence\Repositories\Employee\EmployeeRepository;

class AuthEmployeeUseCase
{

    protected $employeeRepository;
    /**
     *  @var Employee
     */

    protected  $employee;


    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }



    public function logout(LogoutDTO $logoutDTO): DataStatus
    {
        try {
            $response = $this->employeeRepository->logout($logoutDTO);
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
            if (!Hash::check($changePasswordDTO->old_password, $this->employee->password)) {
                return DataFailed(
                    status: false,
                    message: 'The old password is incorrect'
                );
            }

            $response = $this->employeeRepository->update($this->employee->id, $changePasswordDTO);
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
            // dd($this->employee->id);
            $response = $this->employeeRepository->update($this->employee->id, $updateAccountDTO);
            // dd($response);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new EmployeeResource($response)
            );
        } catch (\Exception $e) {
            dd($e->getMessage());
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteAccount(): DataStatus
    {
        try {
            $this->employeeRepository->delete($this->employee->id);
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
            $employee = $this->employeeRepository->checkAuthentication($dto);
            if (!$employee) {
                return DataFailed(
                    status: false,
                    message: 'Unauthinticated - Authentication required'
                );
            }

            return DataSuccess(
                status: true,
                message: 'success',
                data: new ApiEmployeeResource($employee)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function checkTeacherAuthentication(CheckAuthenticationDTO $dto): DataStatus
    {
        try {
            $dto->token = request()->bearerToken();
            $employee = $this->employeeRepository->checkTeacherAuthentication($dto);
            // dd($employee);
            if (!$employee) {
                return DataFailed(
                    status: false,
                    message: 'Unauthinticated - Authentication required'
                );
            }

            return DataSuccess(
                status: true,
                message: 'success',
                data: new ApiEmployeeResource($employee)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchTeacherStudents(EmployeeFilterDTO $dto): DataStatus
    {
        try {
            $studentIds = $this->employeeRepository->fetchTeacherStudents($dto);
            // dd($studentIds); // Debugging line, can be removed later
            return DataSuccess(
                status: true,
                message: 'success',
                data: $studentIds
            );
        } catch (\Exception $e) {
            // dd($e->getMessage()); // Debugging line, can be removed later
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
