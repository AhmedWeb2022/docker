<?php

namespace App\Modules\Course\Http\Resources\Poll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\PollAnswer\PollAnswerResource;

class PollResource extends JsonResource
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
            'content_id' => $this->content_id,
            'image' => $this->image_link,
            'answers' => PollAnswerResource::collection($this->pollAnswers),
        ];
    }
}
