<?php

namespace App\Modules\Course\Http\Requests\Teacher\Review;

use Illuminate\Validation\Rule;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Review\ReviewDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;

class FetchReviewRequest extends BaseRequestAbstract
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
            'user_id' => 'nullable|integer',
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
        });
    }
}
