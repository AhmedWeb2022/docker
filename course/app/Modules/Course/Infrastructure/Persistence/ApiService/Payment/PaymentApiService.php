<?php

namespace App\Modules\Course\Infrastructure\Persistence\ApiService\Payment;

use Illuminate\Support\Facades\Http;

class PaymentApiService
{
    protected $baseUrl;
    public function __construct()
    {
        $this->baseUrl = env('PAYMENT_API_URL');
    }

    public function getPaymentMethod($dto)
    {
        $url =  $this->baseUrl . "dashboard/fetch_payment_method_details";
        $payment = Http::post($url, [
            "payment_method_id" => $dto->payment_method_id,
        ]);

        if ($payment->successful()) {
            return $payment->json();
        } else {
            return null;
        }
    }

    public function getTransaction($dto)
    {
        $url =  $this->baseUrl . "api/fetch_transaction";
        // dd($url);
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $payment = Http::withHeaders($headers)->post($url, [
            "number" => $dto->number,
        ]);
        // dd($payment->json());
        if ($payment->successful()) {
            return $payment->json();
        } else {
            return null;
        }
    }

    public function getTransactionStatus($dto)
    {
        $url =  $this->baseUrl . "api/check_transaction_status";
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $payment = Http::withHeaders($headers)->post($url, [
            "number" => $dto->number,
        ]);
        // dd($payment->json());
        if ($payment->successful()) {
            return $payment->json();
        } else {
            return null;
        }
    }

    public function successTransaction($dto)
    {
        $url =  $this->baseUrl . "api/success_transaction";
        $payment = Http::post($url, [
            "number" => $dto->number,
        ]);

        if ($payment->successful()) {
            return $payment->json();
        } else {
            return null;
        }
    }

    public function failTransaction($dto)
    {
        $url =  $this->baseUrl . "api/fail_transaction";
        $payment = Http::post($url, [
            "number" => $dto->number,
        ]);

        if ($payment->successful()) {
            return $payment->json();
        } else {
            return null;
        }
    }

    public function completeTransactionSubscription($dto)
    {
        $url =  $this->baseUrl . "api/complete_subscription";
        $payment = Http::post($url, [
            "number" => $dto->number,
        ]);

        if ($payment->successful()) {
            return $payment->json();
        } else {
            return null;
        }
    }
}
