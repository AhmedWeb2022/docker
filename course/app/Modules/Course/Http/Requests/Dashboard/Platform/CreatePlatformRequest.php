<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Platform;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Platform\PlatformDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlatformRequest extends BaseRequestAbstract
{
    protected $dtoClass = PlatformDTO::class;
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
            'translations' => 'required|array',
            'image' => 'nullable',
            'cover' => 'nullable',
            'link' => ['nullable', 'url', "unique:platforms,link,except,id"],
            'slug' => ['nullable', 'string', "unique:platforms,slug,except,id"],
        ];
    }
}
