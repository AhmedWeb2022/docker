<?php

namespace App\Modules\User\Infrastructure\Persistence\ApiService;

use Illuminate\Support\Facades\Http;

class StageApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('STAGE_API_URL');
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
}
