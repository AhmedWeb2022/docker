<?php

namespace App\Modules\Course\Infrastructure\Persistence\ApiService\Auth;

use Illuminate\Support\Facades\Http;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;
use Illuminate\Http\Client\ConnectionException;

class TeacherApiService implements AuthenticatesViaToken
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.employee.url');
    }

    public function checkAuth(string $token): array
    {
        return $this->checkTeacherAuth($token); // Existing method
    }



    public function fetchTeachers($teacherIds)
    {
        try {
            // dd($teacherIds); // Debugging line, can be removed later
            $payload = [
                'id' => $teacherIds,
                'role' => 2,
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->timeout(60) // ⏱ timeout in seconds
                ->retry(3, 100) // optional: retry 3 times with 100ms delay
                ->withOptions(['verify' => false])
                ->post($this->baseUrl . 'fetch_employees', $payload);
            // dd($response->json()); // Debugging line, can be removed later
            return $response->json();
        } catch (ConnectionException $e) {
            throw new \Exception("Connection to fetch teachers failed or timed out." . $e->getMessage());
        } catch (\Throwable $ex) {
            throw new \Exception($ex->getMessage());
        }
    }


    public function checkTeacherAuth($token)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeaders([
                    'Accept-Language' => request()->header('Accept-Language'),
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->timeout(60)
                ->retry(3, 100)
                ->withOptions(['verify' => false])
                ->post($this->baseUrl . 'check_teacher_authentication', [
                    'role' => 2
                ]); // Debugging line, can be removed later
            // dd($response->json()); // Debugging line, can be removed later
            return $response->json();
        } catch (ConnectionException $e) {
            throw new \Exception("Connection to check authentication failed or timed out." . $e->getMessage());
        } catch (\Throwable $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkEmployeeExist($employee_ids)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_employee_exists', [
                    'employee_ids' => $employee_ids,
                    'role' => 2, // Assuming role 2 is for teachers
                ]);

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkTeacherExist($teacher_ids)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_employee_exists', [
                    'employee_ids' => $teacher_ids,
                    'role' => 2, // Assuming role 2 is for teachers
                ]);

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
