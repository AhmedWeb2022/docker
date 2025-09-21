<?php

namespace App\Modules\User\Infrastructure\Persistence\ApiService;

use Illuminate\Support\Facades\Http;


class WhatsAppApiService
{
    protected $baseUrl;
    public function __construct()
    {
        $this->baseUrl = env('WHATSAPP_API_URL');
    }
    public function sendMessage($phone, $text, $session, $countryCode = '2')
    {
        try {
            $payload = [
                'chatId'  => $countryCode . $phone . '@c.us',
                'text'    => $text, // Message text
                'session' => $session
            ];
            Http::accept('application/json')
                ->contentType('application/json')
                ->post($this->baseUrl, $payload);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
