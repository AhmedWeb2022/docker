<?php

namespace App\Modules\Admin\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class AdminDTO extends BaseDTOAbstract
{
    public  $admin_id;
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
