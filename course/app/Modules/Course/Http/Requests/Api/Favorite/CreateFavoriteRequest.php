<?php

namespace App\Modules\Course\Http\Requests\Api\Favorite;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Favorite\FavoriteDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateFavoriteRequest extends BaseRequestAbstract
{
    protected $dtoClass = FavoriteDTO::class;
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
            'course_id' => 'required|exists:courses,id',
//            'user_id' => 'required|integer',
        ];
    }
}
