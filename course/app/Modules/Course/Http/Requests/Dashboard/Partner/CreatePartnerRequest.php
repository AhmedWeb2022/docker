<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Partner;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Partner\PartnerDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreatePartnerRequest extends BaseRequestAbstract
{
    protected $dtoClass = PartnerDTO::class;
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
            'translations' => 'required|array',
            'image' => 'required',
            'cover' => 'required',
            'link' => 'required',
            'is_website' => 'required|boolean',
        ];
    }
}
