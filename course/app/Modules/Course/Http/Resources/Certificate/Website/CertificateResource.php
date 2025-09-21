<?php

namespace App\Modules\Course\Http\Resources\Certificate\Website;

use App\Modules\Course\Http\Resources\Partner\PartnerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $about = $request->header('Accept-Language')  !== "*" ? getTranslation('about', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'about');
        $requiremrnts = $request->header('Accept-Language')  !== "*" ? getTranslation('requirements', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'requirements');
        $target_audience = $request->header('Accept-Language')  !== "*" ? getTranslation('target_audience', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'target_audience');
        $benefits = $request->header('Accept-Language')  !== "*" ? getTranslation('benefits', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'benefits');
        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($about) ? 'abouts' : 'about' => $about,
            is_array($requiremrnts) ? 'requirements' : 'requirement' => $requiremrnts,
            is_array($target_audience) ? 'target_audiences' : 'target_audience' => $target_audience,
            is_array($benefits) ? 'benefits' : 'benefit' => $benefits,
            'image' => $this->image_link,
            'is_website' => $this->is_website,
        ];
    }
}
