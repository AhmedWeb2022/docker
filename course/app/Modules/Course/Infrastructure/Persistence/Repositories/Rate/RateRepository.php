<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Rate;


use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\UserApiService;
use App\Modules\Course\Infrastructure\Persistence\Models\Rate\Rate;

class RateRepository extends BaseRepositoryAbstract
{
    protected $userApiService;
    public function __construct()
    {
        $this->setModel(new Rate());
        $this->userApiService = new UserApiService();
    }
    public function getUser($id)
    {
        return $this->userApiService->getUser($id);
    }
}
