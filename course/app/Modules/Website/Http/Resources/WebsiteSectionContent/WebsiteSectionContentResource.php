<?php
namespace App\Modules\Website\Http\Resources\WebsiteSectionContent;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class WebsiteSectionContentResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'website_section_id' => $this->website_section_id,
            'contentable_id' => $this->contentable_id,
            'contentable_type' => $this->contentable_type,
        ];
    }
}
