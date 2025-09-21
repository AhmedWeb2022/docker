<?php

namespace App\Modules\Course\Application\DTOS\Live;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LiveFilterDTO extends BaseDTOAbstract
{
    public $live_id;
    public $content_id;
    public $translations;
    public $teacher_id;
    public $organization_id;
    public $group_id;
    public $course_id;
    public $status;
    public $image;
    public $type;
    public $start_date;
    public $end_date;
    protected string $imageFolder = 'live';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


}
