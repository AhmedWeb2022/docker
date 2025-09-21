<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class CheckAuthenticationDTO extends BaseDTOAbstract
{
    public  $token;
    public $role;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
