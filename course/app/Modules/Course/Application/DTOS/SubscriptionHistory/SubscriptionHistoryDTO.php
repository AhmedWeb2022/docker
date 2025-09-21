<?php

namespace App\Modules\Course\Application\DTOS\SubscriptionHistory;

use ReflectionClass;
use ReflectionProperty;
use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class SubscriptionHistoryDTO extends BaseDTOAbstract
{
    public $subscription_id;
    public $user_id;
    public $notes;
    public $status;
    public $receipt;
    public $price;
    protected string $imageFolder = 'subscription_history/';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }



    public function excludedAttributes(): array
    {
        return [
            'excludeAttributes',
            'subscribtion_id',
            'receipt',
        ]; // Default empty array
    }
}
