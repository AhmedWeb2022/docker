<?php
namespace App\Modules\Website\Http\Resources\WebsiteSection;


use App\Modules\Website\Http\Resources\WebsiteSectionAttachment\WebsiteSectionAttachmentResource;
use App\Modules\Website\Http\Resources\WebsiteSectionContent\WebsiteSectionContentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;



class WebsiteSectionDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language') !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $subtitle = $request->header('Accept-Language') !== "*" ? getTranslation('subtitle', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'subtitle');
        // dd($this->courses);
        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($subtitle) ? 'subtitles' : 'subtitles' => $subtitle,
            'order' => $this->order,
            'status' => $this->status,
            'type' => $this->type,
            'style' => $this->style,
            'parent_id' => $this->parent_id,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'attachments' => WebsiteSectionAttachmentResource::collection($this->attachments),
            'contents' => WebsiteSectionContentResource::collection($this->contents),
            'children' => WebsiteSectionResource::collection($this->children),
        ];
    }
}
