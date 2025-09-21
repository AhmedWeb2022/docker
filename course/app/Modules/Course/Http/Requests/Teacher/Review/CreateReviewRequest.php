<?php

namespace App\Modules\Course\Http\Requests\Teacher\Review;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Review\ReviewDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Illuminate\Validation\Rule;

class CreateReviewRequest extends BaseRequestAbstract
{
    protected $dtoClass = ReviewDTO::class;
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
            "content_id" => 'required|integer|exists:contents,id',
            "user_id" => 'required|integer',
            "follow_up" => 'required|integer',
            "degree_focus" => 'required|integer',
            "interacting_tasks" => 'required|integer',
            "behavior_cooperation" => 'required|integer',
            "progress_understanding" => 'required|integer',
            "notes" => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user_id = $this->input('user_id');


            /** handel user existence */
            $userApiService = app()->make('App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\UserApiService');
            $response = $userApiService->checkUserExist($user_id);
            // dd($response['status']);
            if (isset($response['success']) && !$response['success'] || isset($response['status']) && $response['status'] == false) {
                $validator->errors()->add('user_id', 'The selected user does not exist.');
            }


            /** Handel content that it should be type 6 which is live */
            $content_id = $this->input('content_id');
            $content = Content::find($content_id);
            if (!$content || $content->type != ContentTypeEnum::LIVE->value) {
                $validator->errors()->add('content_id', 'The content must be of type live.');
            }
        });
    }
}
