<?php

namespace App\Modules\Notification\Http\Requests\Notification\Dashboard;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Notification\Application\DTOS\Notification\NotificationDTO;

class CreateNotificationRequest extends BaseRequestAbstract
{
    protected $dtoClass = NotificationDTO::class;
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
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'image' => 'nullable',
            'url' => 'nullable|string',
            'userIds' => 'nullable|array',
            'userIds.*' => 'integer|exists:users,id',
            'topic_id' => 'nullable|integer|exists:topics,id',
        ];
    }
}
