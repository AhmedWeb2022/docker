<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\LiveQuestion;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestion\LiveQuestion;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class LiveQuestionRepository extends BaseRepositoryAbstract
{
    protected $employeeApiService;
    public function __construct()
    {
        $this->setModel(new LiveQuestion());
        $this->employeeApiService = new EmployeeApiService();
    }
}
