<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class UpdateAccountDTO extends BaseDTOAbstract
{
    public  $name;
    public  $email;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
