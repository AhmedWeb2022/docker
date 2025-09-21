<?php

namespace App\Modules\Course\Http\Requests\Api\Content;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Content\ContentFilterDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;

class FetchContentDetailRequest extends BaseRequestAbstract
{
    protected $dtoClass = ContentFilterDTO::class;
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
        // dd($this->toArray());
        // dd( ContentTypeEnum::getValues());
        return [ // data validation
            'course_id' => 'required|integer|exists:courses,id',
            'type' => 'nullable|string|in:' . implode(',', ContentTypeEnum::getValues()),
        ];
    }
}
