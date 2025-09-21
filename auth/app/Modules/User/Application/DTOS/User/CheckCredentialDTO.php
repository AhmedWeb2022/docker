<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class CheckCredentialDTO extends BaseDTOAbstract
{
    public  $email;
    public  $phone;
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
