<?php

namespace App\Modules\Course\Http\Resources\Course\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;

use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;
use App\Modules\Course\Http\Resources\Lesson\Website\LessonResource;
use App\Modules\Course\Http\Resources\Content\Website\ContentResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;
use App\Modules\Course\Http\Resources\Lesson\Website\LessonWithChildrenResource;

class WebsiteCourseContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $teachers = $this->teachers();
        // dd($teachers);
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $cardDescription = $request->header('Accept-Language')  !== "*" ? getTranslation('card_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'card_description');
        $data = [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($cardDescription) ? 'card_descriptions' : 'card_description' => $cardDescription,
            "level_type" => $this->level_type,
            'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
        ];
        // dd($this->level_type);
        if ($this->level_type == CourseLevelTypeEnum::HAS_ONLY_CONTENT->value) {
            $data['course_content'] = ContentResource::collection($this->contents);
        } elseif ($this->level_type == CourseLevelTypeEnum::HAS_LEVEL_AND_CONTENT->value) {
            $data['course_lessons'] = LessonResource::collection($this->lessons);
        } elseif ($this->level_type == CourseLevelTypeEnum::HAS_UNIT_AND_LEVEL_AND_CONTENT->value) {
            // dd($this->lessons);
            $data['course_lessons'] = LessonWithChildrenResource::collection($this->lessons()->whereNull('parent_id')->get());
        }
        $data['teachers'] = $teachers ? $teachers : [];
        return $data;
    }
}
