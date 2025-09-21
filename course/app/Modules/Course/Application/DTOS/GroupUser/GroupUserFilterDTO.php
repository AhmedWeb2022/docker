<?php

namespace App\Modules\Course\Application\DTOS\GroupUser;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class GroupUserFilterDTO extends BaseDTOAbstract
{
    public $group_user_id;
    public $group_id;
    public $user_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
