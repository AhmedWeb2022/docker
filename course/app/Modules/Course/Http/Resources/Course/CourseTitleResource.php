<?php

namespace App\Modules\Course\Http\Resources\Course;


use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;

class CourseTitleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            'level_type' => $this->level_type,

        ];
    }
}
