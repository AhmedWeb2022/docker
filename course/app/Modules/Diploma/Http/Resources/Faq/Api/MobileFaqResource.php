<?php

namespace App\Modules\Diploma\Http\Resources\Faq\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileFaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $answer = $request->header('Accept-Language') !== "*" ? getTranslation('answer', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'answer');
        $question = $request->header('Accept-Language') !== "*" ? getTranslation('question', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'question');


        return [
            'id' => $this->id,
            'answers' => $answer,
            'questions' => $question,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
