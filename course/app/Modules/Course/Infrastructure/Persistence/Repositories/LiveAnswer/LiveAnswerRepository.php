<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswer;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswer\LiveAnswer;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class LiveAnswerRepository extends BaseRepositoryAbstract
{
    protected $employeeApiService;
    public function __construct()
    {
        $this->setModel(new LiveAnswer());
        $this->employeeApiService = new EmployeeApiService();
    }
}
