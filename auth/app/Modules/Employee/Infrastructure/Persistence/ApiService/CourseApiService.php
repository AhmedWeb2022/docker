<?php

namespace App\Modules\Employee\Infrastructure\Persistence\ApiService;

use Illuminate\Support\Facades\Http;
// use Http;

use Illuminate\Http\Client\ConnectionException;

class CourseApiService
{
    protected $baseUrl;
    protected $token;

    protected $teacherUrl;
    protected $courseDashboardUrl;
    public function __construct()
    {
        $this->baseUrl = config('services.course.url');
        $this->teacherUrl = config('services.course.teacher_url');
        $this->courseDashboardUrl = config('services.course.dashboard_url');
    }


    public function fetchTeacherCourses($teacherId)
    {
        try {
            $payload = [
                'teacher_id' => $teacherId,

            ];
            // dd($this->baseUrl);
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_teacher_courses_resource', $payload);
            // dd($response->json());
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function getTeacherCoursesUserIds($token)
    {
        try {
            // dd($teacherIds); // Debugging line, can be removed later


            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->timeout(60) // ⏱ timeout in seconds
                ->retry(3, 100) // optional: retry 3 times with 100ms delay
                ->withOptions(['verify' => false])
                ->get($this->teacherUrl . 'fetch_teacher_courses_user_ids');
            // Debugging line, can be removed later
            return $response->json();
        } catch (ConnectionException $e) {
            throw new \Exception("Connection to fetch teachers failed or timed out." . $e->getMessage());
        } catch (\Throwable $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
    public function createCertificate($certificate, $token)
    {
        try {
            // dd($teacherIds); // Debugging line, can be removed later
            // dd($certificate);
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->timeout(60) // ⏱ timeout in seconds
                ->retry(3, 100) // optional: retry 3 times with 100ms delay
                ->withOptions(['verify' => false])
                ->post($this->courseDashboardUrl . 'create_certificate', $certificate);
            // dd($response->json());
            // Debugging line, can be removed later
            return $response->json();
        } catch (ConnectionException $e) {
            // dd($e);
            throw new \Exception("Connection to fetch teachers failed or timed out." . $e->getMessage());
        } catch (\Throwable $ex) {
            // dd($ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    public function fetchTeacherCertificates($teacherId)
    {
        try {
            // dd($teacherId);
            $payload = [
                'employee_id' => $teacherId,
            ];
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_certificates_resource', $payload);
            // dd($response->json());
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkTeacherHasCourse($teacherId)
    {
        try {
            // dd($teacherId);
            $payload = [
                'teacher_id' => $teacherId,
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_teacher_has_course', $payload);
            // dump($response->json() , 'teacher id' . $teacherId);
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function userCount($teacherId)
    {
        try {
            // dd($teacherId);
            $payload = [
                'teacher_id' => $teacherId,
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_teacher_course_students', $payload);
            // dd($response->json());
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
