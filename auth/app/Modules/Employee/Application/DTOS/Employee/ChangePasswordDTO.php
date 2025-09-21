<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ChangePasswordDTO extends BaseDTOAbstract
{
    public  $old_password;
    public  $new_password;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }



}
