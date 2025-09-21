<?php

namespace App\Modules\Website\Http\Requests\Dashboard\WebsiteSection;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Website\Application\DTOS\WebsiteSection\WebsiteSectionDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class UpdateWebsiteSectionRequest extends BaseRequestAbstract
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
        return [ // data validation
            'website_section_id' => 'required|integer|exists:website_sections,id',
            'translations' => 'sometimes|array',
            'translations.title_ar' => 'sometimes|string',
            'translations.title_en' => 'sometimes|string',
            "translations.description_ar" => "sometimes|string",
            "translations.description_en" => "sometimes|string",
            "translations.subtitle_ar" => "sometimes|string",
            "translations.subtitle_en" => "sometimes|string",
            'order' => 'sometimes|nullable|integer',
            'status' => 'sometimes|nullable|integer',
            'type' => 'sometimes|nullable|integer',
            'style' => 'sometimes|nullable|integer',
            'parent_id' => 'sometimes|nullable|exists:website_sections,id',
            'image' => 'sometimes|nullable|string|max:255',
            'is_active' => 'sometimes|nullable|boolean',
        ];
    }
}
