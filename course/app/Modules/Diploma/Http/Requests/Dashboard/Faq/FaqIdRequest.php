<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Faq;


use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\Faq\FaqDTO;
use App\Modules\Diploma\Application\DTOS\Faq\FaqFilterDTO;

class FaqIdRequest extends BaseRequestAbstract
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

        return [
            "faq_id" => "required|exists:faqs,id",
        ];

    }

    /**
     * Format the validated data before using it.
     *
     * @return FaqDTO
     */
    public function formatted(): FaqDTO
    {
        return FaqDTO::fromArray($this->validated());
    }
}
