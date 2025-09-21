<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\LiveQuestionAttachment;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestionAttachment\LiveQuestionAttachment;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class LiveQuestionAttachmentRepository extends BaseRepositoryAbstract
{
    protected $employeeApiService;
    public function __construct()
    {
        $this->setModel(new LiveQuestionAttachment());
        $this->employeeApiService = new EmployeeApiService();
    }
}
