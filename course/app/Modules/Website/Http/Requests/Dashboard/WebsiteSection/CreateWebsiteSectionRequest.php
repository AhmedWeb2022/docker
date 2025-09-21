<?php

namespace App\Modules\Website\Http\Requests\Dashboard\WebsiteSection;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Website\Application\DTOS\WebsiteSection\WebsiteSectionDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class CreateWebsiteSectionRequest extends BaseRequestAbstract
{
    protected $dtoClass = WebsiteSectionDTO::class;
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
        // dd($this->all());
        return [ // data validation
            'translations' => 'required|array',
            'translations.title_ar' => 'required|string',
            'translations.title_en' => 'required|string',
            "translations.description_ar" => "nullable|string",
            "translations.description_en" => "nullable|string",
            "translations.subtitle_ar" => "nullable|string",
            "translations.subtitle_en" => "nullable|string",
            'order' => 'nullable|integer',
            'status' => 'sometimes|nullable|integer',
            'type' => 'sometimes|nullable|integer',
            'style' => 'sometimes|nullable|integer',
            'parent_id' => 'nullable|exists:website_sections,id',
            'image' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            // Attachments as array of objects
            'attachments' => 'nullable|array',
            'attachments.*.file' => 'nullable|string|max:255',
            'attachments.*.link' => 'nullable|url',
            'attachments.*.alt' => 'nullable|string|max:255',
            // Relation
            'contents' => 'nullable|array',
            'contents.*.course_id' => 'nullable|exists:courses,id',
            'contents.*.diploma_id' => 'nullable|exists:diplomas,id',
        ];
    }
}
