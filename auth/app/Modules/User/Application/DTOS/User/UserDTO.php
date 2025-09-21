<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UserDTO extends BaseDTOAbstract
{
    public  $user_id;
    public  $name;
    public  $username;
    public  $email;
    public  $phone;
    public  $password;
    public  $identifyNumber;
    public $image;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
