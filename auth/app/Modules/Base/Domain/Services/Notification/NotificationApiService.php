<?php


namespace App\Modules\Base\Domain\Services\Notification;

use Illuminate\Support\Facades\Http;
use App\Modules\Notification\Application\DTOS\Notification\NotificationDTO;

class NotificationApiService
{
    protected $baseUrl;
    protected $projectSlug;

    public function __construct()
    {
        $this->baseUrl = env('NOTIFICATION_API_BASE_URL');
        $this->projectSlug = env('NOTIFICATION_PROJECT_SLUG');
    }
    public function subscribeTopic($notificationDTO, $users_tokens)
    {
        $url =  $this->baseUrl . "createTopicAndSubscribe";
        $response = Http::withOptions(['verify' => false])->post($url, [
            'project_slug' => $this->projectSlug,
            'topic_name' => $notificationDTO['topic'],
            'provider' => $notificationDTO['provider'],
            'tokens' => $users_tokens
        ]);
        return $response->json();
    }

    public function sendNotification($notificationDTO)
    {
        // dd($notificationDTO);
        $url =  $this->baseUrl . "api/send_notification";
        // dd($url);
        // dd($notificationDTO->toArray());
        $response = Http::post($url, [
            "title" => $notificationDTO["title"],
            "subtitle" => $notificationDTO["subtitle"],
            "body" => $notificationDTO["body"],
            "user_ids" => $notificationDTO["user_ids"],
            "type" => $notificationDTO["type"],
            "type_id" => $notificationDTO["type_id"],
            "notification_type" => $notificationDTO["notification_type"],
            "is_general" => 1,
        ]);
        // dd($response->json());
        return $response->json();
    }
}
