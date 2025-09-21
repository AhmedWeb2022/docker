<?php

namespace App\Modules\Course\Application\DTOS\Subscription;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class SubscriptionFilterDTO extends BaseDTOAbstract
{
    public $subscription_id;
    public $user_id;
    public $type_id;
    public $type;
    public $payment_method_id;
    public $number;
    public $notes;
    public $price;
    public $has_end_date;
    public $start_date;
    public $end_date;
    public $status;
    public ?string $orderBy = 'created_at'; // Default order by
    public ?string $direction = 'desc'; // Default order direction
    protected string $imageFolder = 'subscription';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
