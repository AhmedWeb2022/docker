<?php

namespace App\Modules\Employee\Infrastructure\Persistence\ApiService;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Client\ConnectionException;

class StageApiService
{
    protected $baseUrl;
    protected $token;
    public function __construct()
    {
        $this->baseUrl = config('services.stage.url');
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

    public function fetchSubjectStageStages($subjectStageId)
    {
        try {
            $payload = [
                'stage_subject_id' => $subjectStageId,
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_stage_subject_stages', $payload);
            // dd($response->json());
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
