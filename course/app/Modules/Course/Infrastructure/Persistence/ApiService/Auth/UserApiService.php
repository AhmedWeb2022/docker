<?php

namespace App\Modules\Course\Infrastructure\Persistence\ApiService\Auth;

use Illuminate\Support\Facades\Http;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;

class UserApiService implements AuthenticatesViaToken
{
    protected $baseUrl;
    private $post;

    public function __construct()
    {
        $this->baseUrl = config('services.user.url');
    }

    public function checkAuth(string $token): array
    {
        return $this->checkUserAuth($token); // Existing method
    }

    public function checkUserAuth($token)
    {
        try {
            // dd($token);
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeaders([
                    'Accept-Language' => request()->header('Accept-Language'),
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->timeout(60) // ⏱ Timeout in seconds (adjust as needed)
                ->retry(3, 100)
                ->withOptions(['verify' => false])
                ->post($this->baseUrl . 'check_authentication');
            // dd($response->json());
            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handles timeout and network issues
            throw new \Exception("Connection timed out or unreachable service." . $e->getMessage());
        } catch (\Throwable $ex) {
            // Handles all other exceptions
            throw new \Exception($ex->getMessage());
        }
    }
    public function getUser($id)
    {
        $this->post = Http::accept('application/json')
            ->contentType('application/json')
            ->withHeaders([
                'Accept-Language' => request()->header('Accept-Language'),
            ])
            ->timeout(60) // ⏱ Timeout in seconds (adjust as needed)
            ->retry(3, 100)
            ->withOptions(['verify' => false])
            ->post($this->baseUrl . 'get_user_by_id', [
                "user_id" => $id
            ]);
        try {
            $response = $this->post;
            return $response->json()['data'] ?? null;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handles timeout and network issues
            throw new \Exception("Connection timed out or unreachable service." . $e->getMessage());
        } catch (\Throwable $ex) {
            // Handles all other exceptions
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkUserExist($user_id)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_user_exists', [
                    'user_id' => $user_id,
                ]);
            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception("Connection to check user existence failed or timed out." . $e->getMessage());
        } catch (\Throwable $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function getUsers($ids)
    {
        try {
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeaders([
                    'Accept-Language' => request()->header('Accept-Language'),
                ])
                ->timeout(60) // ⏱ Timeout in seconds (adjust as needed)
                ->retry(3, 100)
                ->withOptions(['verify' => false])
                ->post($this->baseUrl . 'fetch_users', [
                    "id" => $ids
                ]);

            return $response->json()['data'] ?? null;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handles timeout and network issues
            throw new \Exception("Connection timed out or unreachable service." . $e->getMessage());
        } catch (\Throwable $ex) {
            // Handles all other exceptions
            throw new \Exception($ex->getMessage());
        }
    }
}
