<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

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
