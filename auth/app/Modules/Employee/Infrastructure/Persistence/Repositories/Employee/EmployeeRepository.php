<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Repositories\Employee;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\ApiService\WhatsAppApiService;

use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Employee\Application\DTOS\Certificate\CertificateDTO;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;
use App\Modules\Employee\Infrastructure\Persistence\ApiService\CourseApiService;

class EmployeeRepository extends BaseRepositoryAbstract
{
    /**
     * @var Employee
     */
    protected  $employee;
    protected $whatsAppApiService;
    public function __construct()
    {
        // $this->employee = Auth::user();
        $this->setModel(new Employee());
        $this->whatsAppApiService = new WhatsAppApiService();
    }


    public function login($loginDTO): Employee
    {
        try {
            $credentials = $loginDTO->toArray();
            $fieldType = array_key_first($credentials);
            $employee = $this->getWhere($fieldType, $credentials[$fieldType], 'first');

            if (!$employee || !Hash::check($loginDTO->password, $employee->password)) {
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $employee;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function checkCredential($checkCredentialDTO): Employee
    {
        try {
            $credentials = $checkCredentialDTO->credential();
            $fieldType = array_key_first($credentials);
            // dd($fieldType);
            // dd($credentials[$fieldType]);
            $employee = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            if (!$employee) {
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $employee;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function logout($logoutDTO): bool
    {
        try {
            $employee_device = $this->employee->EmployeeDevices()->where('device_token', $logoutDTO->deviceToken)->first();
            if ($employee_device) {
                $employee_device->delete();
            }
            $this->employee->tokens()->delete();
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

    public function checkAuthentication($dto)
    {
        try {
            $token = $dto->token;
            $accessToken = PersonalAccessToken::findToken($token);
            if (!$accessToken) {
                return false;
            }
            $employee = $accessToken->tokenable;
            if (!$employee) {
                return false;
            }

            return $employee;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function checkTeacherAuthentication($dto)
    {
        try {
            $token = $dto->token;
            $accessToken = PersonalAccessToken::findToken($token);
            if (!$accessToken) {
                return false;
            }
            $employee = $accessToken->tokenable()->where('role', $dto->role)->first();
            if (!$employee) {
                return false;
            }

            return $employee;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function fetchTeacherStudents($dto)
    {
        try {
            $CourseApiService = new CourseApiService();
            $response = $CourseApiService->getTeacherCoursesUserIds($dto->token);
            // dd($response['satus']);
            if (isset($response['status']) && $response['status'] == true) {
                return $response['data'];
            } else {
                throw new \Exception('Failed to fetch teacher students.');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createCertificate($certificates, $employeeId)
    {
        try {
            // dd($certificates);
            $courseApiService = new CourseApiService();
            $createdCertificates = [];
            foreach ($certificates as $certificate) {
                $certificate['employee_id'] = $employeeId; // Ensure employee_id is set
                $token = request()->bearerToken();

                $createdCertificate = $courseApiService->createCertificate($certificate, $token);
                // dd($createdCertificate);
                $createdCertificates[] = $createdCertificate;
            }
            return $createdCertificates;
        } catch (\Exception $e) {
            dd($e);
            throw new \Exception($e->getMessage());
        }
    }
}
