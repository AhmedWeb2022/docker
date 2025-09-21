<?php

namespace App\Modules\Course\Http\Requests\Teacher\Content;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Content\ContentDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;

class FetchContentRequest extends BaseRequestAbstract
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
        // dd($this->toArray());
        return [ // data validation
            'course_id' => 'nullable|integer|exists:courses,id',
            "content_id" => 'nullable|integer|exists:contents,id',
            "lesson_id" => 'nullable|integer|exists:lessons,id',
            "group_id" => 'nullable|integer|exists:groups,id',
            'type' => 'nullable|integer|in:' . implode(',', ContentTypeEnum::getValues()),
            'parent_id' => 'nullable|integer|exists:contents,id',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $groupId = $this->input('group_id');
            if ($type != ContentTypeEnum::LIVE->value) {
                if ($groupId) {
                    $validator->errors()->add('group_id', 'Group ID is not applicable for this content type.');
                }
            }
        });
    }
}
