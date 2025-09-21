<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class UpdateAccountDTO extends BaseDTOAbstract
{
    public  $name;
    public  $username;
    public  $email;
    public  $phone;
    public  $stage_id;
    public  $location_id;
    public  $nationality_id;
    public  $image;
    public $phone_code;
    public $gender;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
