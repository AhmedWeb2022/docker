<?php

namespace App\Modules\Admin\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ResetPasswordDTO extends BaseDTOAbstract
{
    public  $email;
    public  $phone;
    public  $code;
    public  $password;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


    public function credential(): array
    {
        return array_filter([
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }
}
