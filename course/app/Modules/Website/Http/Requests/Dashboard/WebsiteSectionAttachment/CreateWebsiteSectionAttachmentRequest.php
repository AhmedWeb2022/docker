<?php

namespace App\Modules\Website\Http\Requests\Dashboard\WebsiteSectionAttachment;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Website\Application\DTOS\WebsiteSectionAttachment\WebsiteSectionAttachmentDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class CreateWebsiteSectionAttachmentRequest extends BaseRequestAbstract
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
    public function CustomRules(): array
    {
        return [ // data validation
            'website_section_id' => 'required|exists:website_sections,id',
            'file' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'alt' => 'nullable|string|max:255',
        ];
    }
}
