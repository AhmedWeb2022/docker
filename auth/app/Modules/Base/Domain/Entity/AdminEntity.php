<?php

namespace App\Modules\Base\Domain\Entity;


class AdminEntity
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
        // Get the ID of the Admin
        return   $this->id;
    }
    public function getName()
    {
        // Get the name of the Admin
        return  $this->name;
    }

    public static function example(): AdminEntity
    {
        return new AdminEntity(id: 1, name: 'John Doe');
    }

    public static function authAdmin(): AdminEntity
    {
        return new AdminEntity(id: auth('admin')->user()->id, name: auth('admin')->user()->name);
    }
}
