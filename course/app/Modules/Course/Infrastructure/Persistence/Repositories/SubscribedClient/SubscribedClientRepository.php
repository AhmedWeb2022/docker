<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\SubscribedClient;



use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

use App\Modules\Course\Infrastructure\Persistence\Models\SubscribedClient\SubscribedClient;

class SubscribedClientRepository extends BaseRepositoryAbstract
{

    protected $paymentApiService;
    protected $notificationApiService;
    protected $userApiService;
    public function __construct()
    {
        $this->setModel(new SubscribedClient());
    }
}
