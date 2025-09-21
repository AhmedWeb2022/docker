<?php

namespace App\Modules\Admin\Application\DTOS\Admin;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class AdminFilterDTO extends BaseDTOAbstract
{
    public  $name;
    public  $email;
    protected bool $excludeAttributes = true;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
