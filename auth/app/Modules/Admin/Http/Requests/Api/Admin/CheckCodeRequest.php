<?php

namespace App\Modules\Admin\Http\Requests\Api\Admin;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Admin\Application\DTOS\Admin\CheckCodeDTO;


class CheckCodeRequest extends BaseRequestAbstract
{
    protected  $dtoClass = CheckCodeDTO::class;
    /**
     * Determine if the Admin is authorized to make this request.
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
            'credential' => 'required|string',
            'code' => 'required|string',
        ];
    }


}
