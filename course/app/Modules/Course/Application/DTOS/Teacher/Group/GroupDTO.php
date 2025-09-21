<?php

namespace App\Modules\Course\Application\DTOS\Teacher\Group;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class GroupDTO extends BaseDTOAbstract
{
    public $group_id;
    public $translations;
    public $organization_id;
    public $image;
    public $course_id;
    public $start_date;
    public $end_date;
    public $student_ids;
    protected string $imageFolder = 'group';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
