<?php

namespace App\Modules\Employee\Application\DTOS\EmployeeSubjectStage;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;
use App\Modules\Employee\Infrastructure\Persistence\ApiService\StageApiService;

class EmployeeSubjectStageDTO extends BaseDTOAbstract
{
    public $employee_id;
    public  $subject_stage_id;
    public  $subject_id;
    public  $stage_id;
    public $employee_subject_stage_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
