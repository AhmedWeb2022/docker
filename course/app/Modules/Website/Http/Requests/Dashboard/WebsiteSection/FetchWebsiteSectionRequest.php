<?php

namespace App\Modules\Website\Http\Requests\Dashboard\WebsiteSection;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Website\Application\DTOS\WebsiteSection\WebsiteSectionDTO;

class FetchWebsiteSectionRequest extends BaseRequestAbstract
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
    public function customRules(): array
    {
        return [ // data validation
            'website_section_id' => 'nullable|integer|exists:website_sections,id',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'word' => 'nullable|string',
        ];
    }
}
