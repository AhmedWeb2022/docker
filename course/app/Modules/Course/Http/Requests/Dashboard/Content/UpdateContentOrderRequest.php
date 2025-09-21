<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Content;

use Illuminate\Validation\Rule;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Content\ContentDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class UpdateContentOrderRequest extends BaseRequestAbstract
{
    protected $dtoClass = ContentDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function customRules(): array
    {
        return [
            'content_id' => 'required|integer|exists:contents,id',
            'order' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $contentOrder   = Content::max('order');
            $order = $this->get('order');
            // dd($isFile);
            if ($order > $contentOrder) {
                $validator->errors()->add('order', 'The order must not be greater than ' . $contentOrder);
            }

            
        });
    }
}
