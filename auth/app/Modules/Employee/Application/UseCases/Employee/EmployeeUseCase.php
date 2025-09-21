<?php

namespace App\Modules\Employee\Application\UseCases\Employee;




use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Modules\Base\Domain\Holders\EmployeeHolder;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Employee\Application\DTOS\Employee\LoginDTO;
use App\Modules\Employee\Application\DTOS\Employee\EmployeeDTO;
use App\Modules\Employee\Application\DTOS\Employee\RegisterDTO;
use App\Modules\Employee\Application\DTOS\Employee\CheckCodeDTO;
use App\Modules\Employee\Domain\Services\Email\EmailNotification;
use App\Modules\Employee\Http\Resources\Employee\EmployeeResource;
use App\Modules\Employee\Application\DTOS\Employee\ResetPasswordDTO;
use App\Modules\Employee\Application\DTOS\Certificate\CertificateDTO;
use App\Modules\Employee\Application\DTOS\Employee\EmployeeFilterDTO;
use App\Modules\Employee\Http\Resources\Employee\ApiEmployeeResource;
use App\Modules\Employee\Application\DTOS\Employee\CheckCredentialDTO;
use App\Modules\Employee\Application\DTOS\Employee\CheckEmployeeExistDTO;
use App\Modules\Employee\Application\DTOS\Employee\CheckAuthenticationDTO;
use App\Modules\Employee\Application\DTOS\EmployeeSubjectStage\EmployeeSubjectStageDTO;
use App\Modules\Employee\Infrastructure\Persistence\Repositories\Employee\EmployeeRepository;


class EmployeeUseCase
{

    protected $employeeRepository;
    /**
     *  @var Employee
     */

    protected $employee;


    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository();
        // $this->employee = EmployeeHolder::getEmployee();
    }

    /**
     * Register a new Employee.
     *
     * @param RegisterDTO $registerDTO
     * @return DataStatus
     */
    public function register(RegisterDTO $registerDTO): DataStatus
    {
        try {
            $employee = $this->employeeRepository->create($registerDTO);
            if ($employee) {

                if ($registerDTO->EmployeeDevice() != []) {
                    $employee->EmployeeDevices()->create($registerDTO->EmployeeDevice());
                }
                $employee['api_token'] = $employee->createToken('api_token')->plainTextToken;
                $credentials = $registerDTO->credential();
                $fieldType = checkCredentialType($credentials);
                if ($employee->is_email_verified || $employee->is_phone_verified) {
                    $employee->sendOtp($fieldType, $this->employeeRepository);
                }
            }
            return DataSuccess(
                status: true,
                message: 'success',
                data: new EmployeeResource($employee)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Log in a Employee.
     *
     * @param LoginDTO $loginDTO
     * @return DataStatus
     */
    public function login(LoginDTO $loginDTO): DataStatus
    {
        try {
            $employee = $this->employeeRepository->login($loginDTO);
            if ($employee) {

                if ($loginDTO->EmployeeDevice() != []) {
                    $employee->EmployeeDevices()->updateOrCreate(['device_token' => $loginDTO->device_token], $loginDTO->EmployeeDevice());
                }
                $employee['api_token'] = $employee->createToken('api_token')->plainTextToken;
                $credentials = $loginDTO->credential();
                $fieldType = checkCredentialType($credentials);
                if ($employee->is_email_verified || $employee->is_phone_verified) {
                    $employee->sendOtp($fieldType, $this->employeeRepository);
                }
            }
            // dd($employee);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new EmployeeResource($employee)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Check Employee credentials and send OTP if valid.
     *
     * @param CheckCredentialDTO $checkCredentialDTO
     * @return DataStatus
     */
    public function checkCredential(CheckCredentialDTO $checkCredentialDTO): DataStatus
    {
        try {
            $employee = $this->employeeRepository->checkCredential($checkCredentialDTO);
            if ($employee) {
                $otp = generateOTP();
                $credentials = $checkCredentialDTO->credential();
                $fieldType = checkCredentialType($credentials);
                $employee->sendOtp($fieldType, $this->employeeRepository);
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
            $Employee = $this->employeeRepository->checkCredential($checkCodeDTO);

            $credentials = $checkCodeDTO->credential();
            $fieldType = checkCredentialType($credentials);
            $cachedOtp = getCache("otp_{$Employee->$fieldType}");

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
     * Reset the Employee's password using the provided OTP code.
     *
     * @param ResetPasswordDTO $resetPasswordDTO
     * @return DataStatus
     */
    public function resetPassword(ResetPasswordDTO $resetPasswordDTO): DataStatus
    {
        try {
            $Employee = $this->employeeRepository->checkCredential($resetPasswordDTO);
            $credentials = $resetPasswordDTO->credential();
            $fieldType = checkCredentialType($credentials);
            $cachedOtp = getCache("otp_{$Employee->$fieldType}");
            if (!$cachedOtp || !checkOTP($resetPasswordDTO->code, $cachedOtp)) {
                return DataFailed(
                    status: false,
                    message: 'The provided code is incorrect.'
                );
            }
            $this->employeeRepository->update($Employee->id, $resetPasswordDTO);
            // Remove OTP from cache after successful reset
            forgetCache("otp_{$Employee->$fieldType}");
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

    public function fetchEmployees(EmployeeFilterDTO $employeeFilterDTO, $view = 'dashboard'): DataStatus
    {
        try {
            $employees = $this->employeeRepository->filter(
                dto: $employeeFilterDTO,
                paginate: $employeeFilterDTO->paginate,
                searchableFields: ['name', 'email', 'phone'],
                limit: $employeeFilterDTO->limit,
                whereHasRelations: $employeeFilterDTO->subject_stage_id ? [
                    'subjectStages' => [
                        "subject_stage_id" => $employeeFilterDTO->subject_stage_id
                    ]
                ] : []
            );
            if ($employeeFilterDTO->has_course) {
                $employees = $employees->filter(function ($employee) {
                    return $employee->hasCourse();
                });
            }
            $resource = $view === 'api' ? ApiEmployeeResource::collection($employees) : EmployeeResource::collection($employees);
            return DataSuccess(
                status: true,
                message: 'success',
                data: $employeeFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchEmployeeDetails(EmployeeDTO $EmployeeDTO): DataStatus
    {
        try {
            $Employee = $this->employeeRepository->getById($EmployeeDTO->employee_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new EmployeeResource($Employee)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createEmployee(EmployeeDTO $employeeDTO): DataStatus
    {
        try {
            // dd($employeeDTO);
            $employee = $this->employeeRepository->create($employeeDTO);
            if (isset($employeeDTO->certificates) && is_array($employeeDTO->certificates) && count($employeeDTO->certificates) > 0) {
                // dd($employeeDTO->certificates);
                $this->employeeRepository->createCertificate($employeeDTO->certificates, $employee->id);
                // dd($certificates);
            }

            // dd($employeeDTO->socials);
            return DataSuccess(
                status: true,
                message: 'success',
                data: $employee
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateEmployee(EmployeeDTO $EmployeeDTO): DataStatus
    {
        try {
            $employee = $this->employeeRepository->update($EmployeeDTO->employee_id, $EmployeeDTO);

            return DataSuccess(
                status: true,
                message: 'success',
                data: $employee
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteEmployee(EmployeeDTO $EmployeeDTO): DataStatus
    {
        try {
            $Employee = $this->employeeRepository->delete($EmployeeDTO->employee_id);
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

    public function checkEmployeeExists(CheckEmployeeExistDTO $checkEmployeeExistDTO): DataStatus
    {
        try {
            foreach ($checkEmployeeExistDTO->employee_ids as $employee_id) {

                $employee = $this->employeeRepository->getMultibleWhere([
                    'id' => $employee_id,
                    'role' => $checkEmployeeExistDTO->role
                ], 'first');
                if (!$employee) {
                    $message = $checkEmployeeExistDTO->role == 1 ? 'Employee' : 'Teacher';
                    return DataFailed(
                        status: false,
                        message: $message . " with id {$employee_id} not found"
                    );
                }
            }
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
}
