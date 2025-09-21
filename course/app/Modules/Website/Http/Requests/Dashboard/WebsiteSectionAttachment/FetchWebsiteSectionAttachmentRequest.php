<?php

namespace App\Modules\Website\Http\Requests\Dashboard\WebsiteSectionAttachment;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Website\Application\DTOS\WebsiteSectionAttachment\WebsiteSectionAttachmentDTO;

class FetchWebsiteSectionAttachmentRequest extends BaseRequestAbstract
{
    protected $dtoClass = WebsiteSectionAttachmentDTO::class;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function customRules(): array
    {
        return [ // data validation
            'website_section_attachment_id' => 'nullable|integer|exists:website_section_attachments,id',
            'file' => 'nullable|string|max:255',
            'link'=> 'nullable|url',
            'alt'=> 'nullable|string|max:255',
            'word' => 'nullable|string',
        ];
    }
}
