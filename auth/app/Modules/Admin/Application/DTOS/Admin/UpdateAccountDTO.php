<?php

namespace App\Modules\Admin\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class UpdateAccountDTO extends BaseDTOAbstract
{
    public  $name;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
