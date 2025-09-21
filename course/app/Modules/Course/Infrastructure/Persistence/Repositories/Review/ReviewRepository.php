<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Review;


use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\UserApiService;
use App\Modules\Course\Infrastructure\Persistence\Models\Review\Review;

class ReviewRepository extends BaseRepositoryAbstract
{
    protected $userApiService;
    public function __construct()
    {
        $this->setModel(new Review());
        $this->userApiService = new UserApiService();
    }
    public function getUser($id)
    {
        return $this->userApiService->getUser($id);
    }
}
