<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UserFilterDTO extends BaseDTOAbstract
{
    public  $id;
    public  $name;
    public  $email;
    public  $phone;
    public  $username;
    public $stage_id;
    public $user_ids;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
