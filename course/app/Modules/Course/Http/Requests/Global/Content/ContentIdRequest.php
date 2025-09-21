<?php

namespace App\Modules\Course\Http\Requests\Global\Content;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Content\ContentDTO;
use App\Modules\Course\Application\DTOS\Content\ContentFilterDTO;

class ContentIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = ContentDTO::class;
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
            'content_id' => 'required|integer|exists:contents,id',
        ];
    }
}
