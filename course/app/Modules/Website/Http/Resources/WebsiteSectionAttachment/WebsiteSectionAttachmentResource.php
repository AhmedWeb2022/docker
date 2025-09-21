<?php
namespace App\Modules\Website\Http\Resources\WebsiteSectionAttachment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class WebsiteSectionAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'website_section_id' => $this->website_section_id,
            'file' => $this->file,
            'link' => $this->link,
            'alt' => $this->alt,
        ];
    }
}
