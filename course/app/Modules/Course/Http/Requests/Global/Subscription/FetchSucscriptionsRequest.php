<?php

namespace App\Modules\Course\Http\Requests\Global\Subscription;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Subscription\SubscriptionDTO;
use App\Modules\Course\Application\DTOS\Subscription\SubscriptionFilterDTO;

class FetchSucscriptionsRequest extends BaseRequestAbstract
{
    protected $dtoClass = SubscriptionFilterDTO::class;
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
        ];
    }
}
