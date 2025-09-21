<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswerAttachment;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswerAttachment\LiveAnswerAttachment;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class LiveAnswerAttachmentRepository extends BaseRepositoryAbstract
{
    protected $employeeApiService;
    public function __construct()
    {
        $this->setModel(new LiveAnswerAttachment());
        $this->employeeApiService = new EmployeeApiService();
    }
}
