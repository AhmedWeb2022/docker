<?php
namespace App\Modules\Website\Http\Resources\WebsiteSection;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteSectionResource extends JsonResource
{
    public function toArray($request)
    {
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language') !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $subtitle = $request->header('Accept-Language') !== "*" ? getTranslation('subtitle', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'subtitle');
        return [
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($subtitle) ? 'card_descriptions' : 'card_description' => $subtitle,
            'id' => $this->id,
            'order' => $this->order,
            'status' => $this->status,
            'type' => $this->type,
            'style' => $this->style,
            'parent_id' => $this->parent_id,
            'image' => $this->image,
            'is_active' => $this->is_active,
        ];
    }
}
