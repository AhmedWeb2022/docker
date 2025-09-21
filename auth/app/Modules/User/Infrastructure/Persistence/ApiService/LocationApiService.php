<?php

namespace App\Modules\User\Infrastructure\Persistence\ApiService;

use Illuminate\Support\Facades\Http;

class LocationApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('LOCATION_API_URL');
    }
    public function fetchLocationDetails($location_id)
    {
        // dd(request()->header('Accept-Language'));
        try {
            $payload = [
                'location_id' => $location_id,
            ];

            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_location_details', $payload);

            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
