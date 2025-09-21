<?php

namespace App\Modules\Course\Http\Resources\CourseSetting;

use App\Modules\Course\Http\Resources\Video\VideoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'code_status' => $this->code_status ?? null,
            'is_security' => $this->is_security ?? false,
            'is_watermark' => $this->is_watermark ?? false,
            'is_voice' => $this->is_voice ?? false,
            'is_emulator' => $this->is_emulator ?? false,
            'time_number' => $this->time_number ?? null,
            'number_of_voice' => $this->number_of_voice ?? null,
            'watch_video' => $this->watch_video ?? null,
            'number_watch_video' => $this->number_watch_video ?? null,
        ];
    }
}
