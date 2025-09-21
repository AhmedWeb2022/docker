<?php

namespace App\Modules\Course\Http\Resources\Content\Website;

use Illuminate\Http\Request;
use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Http\Resources\Poll\PollResource;
use App\Modules\Course\Http\Resources\Audio\FullAudioResource;
use App\Modules\Course\Http\Resources\Document\DocumentResource;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Http\Resources\Reference\ReferanceResource;
use App\Modules\Course\Http\Resources\Session\FullSessionResource;
use App\Modules\Course\Http\Resources\Document\FullDocumentResource;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Exam\ExamApiService;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        // dd($user);
        // dd($this->isViewed($user->id));
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $data = [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            'type' => $this->type,
            'status' => $this->status,
            'image' => $this->image_link,
            'is_viewed' => $user ? $this->isViewed($user->id) : false,
            'can_view' => $user ? $this->canUserViewContent($this->resource, $user->id) : false,
            'is_finished' => $user ? $this->isFinished($user->id) : false,
            'can_skip' => $this->can_skip,
            'skip_rate' => $this->skip_rate,
            'stops' => $user ? $this->userViewStops($user->id) : 0,
            'content' => $this->handelContentResource(),
            'references' => ReferanceResource::collection($this->referances),
        ];
        $this->course_id != null ? $data['course_id'] = $this->course_id : null;
        $this->level_id != null ? $data['level_id'] = $this->level_id : null;
        $this->lesson_id != null ? $data['lesson_id'] = $this->lesson_id : null;
        return $data;
    }

    private function handelContentResource()
    {
        if ($this->type == ContentTypeEnum::SESSION->value) {
            return new FullSessionResource($this->session);
        } elseif ($this->type == ContentTypeEnum::AUDIO->value) {
            return new FullAudioResource($this->audio);
        } elseif ($this->type == ContentTypeEnum::DOCUMENT->value) {
            return new FullDocumentResource($this->document);
        } elseif ($this->type == ContentTypeEnum::POLL->value) {
            return new PollResource($this->poll);
        } elseif ($this->type == ContentTypeEnum::EXAM->value) {

            $examApiService = new ExamApiService();
            $exam = $examApiService->getExan([
                "content_id" => $this->id
            ]);
            return $exam;
        }
        return null;
    }
}
