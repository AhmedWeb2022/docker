<?php

namespace App\Modules\Course\Infrastructure\Persistence\ApiService\Exam;

use Illuminate\Support\Facades\Http;

class ExamApiService
{
    protected $baseUrl;
    public function __construct()
    {
        $this->baseUrl = env('EXAM_API_URL');
    }

    public function getExan($dto)
    {
        // $token = $dto['token'];
        $url =  $this->baseUrl . "fetch_exam_contents";
        $exam = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Language' => request()->header('Accept-Language'),
        ])->post($url, [
            "content_id" => $dto['content_id'],
        ]);
        // dd($exam->json());
        if ($exam->successful()) {
            return $exam->json()['data'];
        } else {
            return null;
        }
    }
}
