<?php

namespace App\Modules\Course\Infrastructure\Persistence\ApiService\Stage;

use Illuminate\Support\Facades\Http;

class StageApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.stage.url');
    }
    public function fetchStageDetails($stage_id)
    {
        // dd(request()->header('Accept-Language'));
        try {
            $payload = [
                'stage_id' => $stage_id,
                'with_children' => true,
                'reverse' => true
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_stage_details', $payload);

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function fetchSubjectDetails($subject_id)
    {
        // dd(request()->header('Accept-Language'));
        try {
            $payload = [
                'subject_id' => $subject_id,
                'with_children' => true,
                'reverse' => true
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_subject_details', $payload);

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkStageExist($stage_id)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_stage_exist', [
                    'stage_id' => $stage_id,
                ]);

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkSubjectExist($subject_id)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_subject_exist', [
                    'subject_id' => $subject_id,
                ]); // 👈 disables SSL verification

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }


    public function fetchSubjectStages($subjectStageId)
    {
        try {
            $payload = [
                'stage_subject_id' => $subjectStageId,
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_stage_subjects', $payload);
            // dd($response->json());
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
