<?php

namespace App\Modules\Base\Domain\Holders;

use App\Modules\Base\Domain\Entity\AdminEntity;


class AdminHolder
{

    public static function getAdmin(): AdminEntity
    {
        // Return the Admin entity
        $admin =  AdminEntity::authAdmin();
        return $admin;
    }
}
