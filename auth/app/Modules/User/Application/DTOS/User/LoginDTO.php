<?php

namespace App\Modules\User\Application\DTOS\User;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\User\Domain\DTO\UserDTOInterface;

class LoginDTO extends BaseDTOAbstract
{
    public  $email;
    public  $phone;
    public  $password;
    public  $device_token;
    public  $device_id;
    public  $device_type;
    public  $device_os;
    public  $device_os_version;
    public function __construct(array $data = [])
    {
        if (isset($data['credential'])) {
            if (filter_var($data['credential'], FILTER_VALIDATE_EMAIL)) {
                $data['email'] = $data['credential'];
            } else {
                $data['phone'] = $data['credential'];
            }
            unset($data['credential']);
        }
        parent::__construct($data);
    }




    public function UserDevice(): array
    {
        return array_filter([
            'device_id' => $this->device_id,
            'device_token' => $this->device_token,
            'device_type' => $this->device_type,
            'device_os' => $this->device_os,
            'device_os_version' => $this->device_os_version,
        ]);
    }
    public function credential(): array
    {
        return array_filter([
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }
}
