<?php

namespace App\Modules\Base\Domain\Entity;


class UserEntity
{

    public int $id;
    public string $name;

    public function __construct(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;
    }


    public function getId()
    {
        // Get the ID of the user
        return   $this->id;
    }
    public function getName()
    {
        // Get the name of the user
        return  $this->name;
    }

    public static function example(): UserEntity
    {
        return new UserEntity(id: 1, name: 'John Doe');
    }

    public static function authUser(): UserEntity
    {
        return new UserEntity(id: auth('api')->user()->id, name: auth('api')->user()->name);
    }
}
