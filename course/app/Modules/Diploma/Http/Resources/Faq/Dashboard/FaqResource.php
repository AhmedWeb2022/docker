<?php

namespace App\Modules\Diploma\Http\Resources\Faq\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
        $answers = getTranslationAndLocale($this?->translations, 'answer');
        $questions = getTranslationAndLocale($this?->translations, 'question');
        
        return [
            'id' => $this->id,
            'answers' => $answer ? $answers : [],
            'answer' => $answer ?? '',
            'questions' => $question ? $questions : [],
            'question' => $question ?? '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
