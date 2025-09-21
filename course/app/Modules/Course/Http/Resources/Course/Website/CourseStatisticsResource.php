<?php

namespace App\Modules\Course\Http\Resources\Course\Website;

use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Course\Http\Resources\Certificate\CertificateResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Rate\RateResource;
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

class CourseStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // $teachers = $this->teachers();
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);

        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $cardDescription = $request->header('Accept-Language')  !== "*" ? getTranslation('card_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'card_description');
        $data = [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($cardDescription) ? 'card_descriptions' : 'card_description' => $cardDescription,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'watched_video' => $user
                ? $this->courseEntity()->getWatchedContentStatistics($user->id, ContentTypeEnum::SESSION->value, 'videos')
                : [],
            'opened_files' => $user
                ? $this->courseEntity()->getWatchedContentStatistics($user->id, ContentTypeEnum::DOCUMENT->value, 'files')
                : [],
            'completion_audio' => $user
                ? $this->courseEntity()->getWatchedContentStatistics($user->id, ContentTypeEnum::AUDIO->value, 'audios')
                : [],
            'passed_quizzes' => $user
                ? $this->courseEntity()->getWatchedContentStatistics($user->id, ContentTypeEnum::EXAM->value, 'quizzes')
                : [],

            'completion_percentage' => $user
                ? $this->courseEntity()->getAllWatchedContentPercentage($user->id)
                : [],
            'attendance_percentage' => 50,


        ];
        return $data;
    }
}
