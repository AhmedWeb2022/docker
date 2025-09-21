<?php

namespace App\Modules\Diploma\Http\Resources\DiplomaAbout;

use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaTarget\DiplomaTarget;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class DiplomaAboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        return [    
            "id" => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
        ];
    }


}
