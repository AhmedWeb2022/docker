<?php

namespace App\Modules\Course\Http\Requests\Global\Subscription;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Subscription\SubscriptionDTO;

class SubscriptionRequest extends BaseRequestAbstract
{
    protected $dtoClass = SubscriptionDTO::class;
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
            'type_id' => 'required|integer',
            'type' => 'required|integer',
            'payment_method_id' => 'nullable|integer',
            'number' => 'nullable|string',
//            'price' => 'required|numeric',
            'price' => 'nullable|numeric',
            'has_end_date' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'required_if:has_end_date,1|date|after:start_date',
            'notes' => 'nullable|string',
            "is_join" => "nullable|boolean",
            "has_receipt" => "nullable|boolean",
            "receipt" => "required_if:has_receipt,1|string",
        ];
    }
}
