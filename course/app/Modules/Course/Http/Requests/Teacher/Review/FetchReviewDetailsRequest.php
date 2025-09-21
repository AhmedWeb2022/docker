<?php

namespace App\Modules\Course\Http\Requests\Teacher\Review;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Review\ReviewDTO;
use Illuminate\Validation\Rule;

class FetchReviewDetailsRequest extends BaseRequestAbstract
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
            "review_id" => 'nullable|integer|exists:reviews,id',
        ];
    }
}
