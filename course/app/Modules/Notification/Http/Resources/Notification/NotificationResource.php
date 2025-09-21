<?php

namespace App\Modules\Notification\Http\Resources\Notification;

use Illuminate\Http\Request;
use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->subtitle,
            'image' => $this->image_link,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'is_read' => $this->isRead($user) == null || $this->isRead($user)->is_read == false ? false : true
        ];
    }
}
