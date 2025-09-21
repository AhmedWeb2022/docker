<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Faq;

use App\Modules\Diploma\Application\DTOS\Faq\FaqDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Application\DTOS\Base\BaseDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\Faq\FaqFilterDTO;

class FetchFaqRequest extends BaseRequestAbstract
{
        protected $dtoClass = FaqFilterDTO::class;
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
    public function rules(): array
    {
        return [ // data validation
            'paginate' => 'nullable|boolean',
            "per_page" => 'nullable|integer',
            "word" => 'nullable|string',
            "diploma_id" => 'nullable|integer|exists:diplomas,id',  
        ];
    }


}
