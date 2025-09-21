<?php

namespace App\Modules\Course\Http\Requests\Api\ContentView;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\ContentView\ContentViewDTO;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;

class CreateContentViewRequest extends BaseRequestAbstract
{
    protected $dtoClass = ContentViewDTO::class;
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
            'content_id' => 'required|integer|exists:contents,id',
            'is_finished' => 'required|boolean',
            'stops' => 'required_if:is_finished,false|integer',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $content_id = $this->input('content_id');
            $content = Content::find($content_id);
            $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
            // dd($user);
            if ($content) {
                $can_view =  $content->canUserViewContent($content, $user->id);
                if (!$can_view) {
                    $validator->errors()->add('content_id', 'You can not view this content');
                }
            }
        });
    }
}
