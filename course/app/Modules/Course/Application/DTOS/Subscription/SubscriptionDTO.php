<?php

namespace App\Modules\Course\Application\DTOS\Subscription;

use ReflectionClass;
use ReflectionProperty;
use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class SubscriptionDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true; // Default is false
    public $subscription_id;
    public $user_id; // required fora all subscriptions
    public $type_id; // required fora all subscriptions
    public $type; // required for all subscriptions
    public $payment_method_id; // required for online payment only
    public $number; // required for online payment only
    public $notes;
    public $price; // required for online payment only
    public $has_end_date;
    public $start_date;
    public $end_date;
    public $status;
    public $has_receipt;
    public $receipt;
    public $is_join;
    protected string $imageFolder = 'subscription';
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
