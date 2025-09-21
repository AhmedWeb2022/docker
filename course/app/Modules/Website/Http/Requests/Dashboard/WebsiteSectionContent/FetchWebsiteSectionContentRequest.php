<?php

namespace App\Modules\Website\Http\Requests\Dashboard\WebsiteSectionContent;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Website\Application\DTOS\WebsiteSectionContent\WebsiteSectionContentDTO;

class FetchWebsiteSectionContentRequest extends BaseRequestAbstract
{
    protected $dtoClass = WebsiteSectionContentDTO::class;
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
            'website_section_content_id' => 'nullable|integer|exists:website_section_contents,id',
            'word' => 'nullable|string',
        ];
    }
}
