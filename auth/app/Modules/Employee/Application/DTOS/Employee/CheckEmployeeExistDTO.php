<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CheckEmployeeExistDTO extends BaseDTOAbstract
{
    public  $employee_ids;
    public $role;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
