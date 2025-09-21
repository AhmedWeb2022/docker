<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\SubscriptionHistory;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\SubscriptionHistory\SubscriptionHistory;

class SubscriptionHistoryRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new SubscriptionHistory());
    }
}
