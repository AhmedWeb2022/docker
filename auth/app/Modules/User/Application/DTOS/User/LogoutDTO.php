<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class LogoutDTO extends BaseDTOAbstract
{
    public  $deviceToken;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
