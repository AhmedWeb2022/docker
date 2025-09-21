<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class ChangePasswordDTO extends BaseDTOAbstract
{
    public  $old_password;
    public  $new_password;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
