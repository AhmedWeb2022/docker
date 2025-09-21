<?php

namespace App\Modules\Course\Http\Requests\Api\SubscribedClient;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\SubscribedClient\SubscribedClientDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubscribedClientRequest extends BaseRequestAbstract
{
    protected $dtoClass = SubscribedClientDTO::class;
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
            'email' => 'required|email|unique:subscribed_clients,email',
        ];
    }
}
