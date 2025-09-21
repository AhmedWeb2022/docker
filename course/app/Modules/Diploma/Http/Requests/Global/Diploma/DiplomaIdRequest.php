<?php

namespace App\Modules\Diploma\Http\Requests\Global\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaFilterDTO;

class DiplomaIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaFilterDTO::class;
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
            'diploma_id' => 'required|integer|exists:diplomas,id',
        ];
    }


}
