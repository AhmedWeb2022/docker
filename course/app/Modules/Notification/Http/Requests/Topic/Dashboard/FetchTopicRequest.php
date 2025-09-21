<?php

namespace App\Modules\Notification\Http\Requests\Topic\Dashboard;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Notification\Application\DTOS\Topic\TopicFilterDTO;

class FetchTopicRequest extends BaseRequestAbstract
{
    protected $dtoClass = TopicFilterDTO::class;
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
            'topic_id' => 'nullable|integer|exists:topics,id',
        ];
    }
}
