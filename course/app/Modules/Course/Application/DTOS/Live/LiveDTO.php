<?php

namespace App\Modules\Course\Application\DTOS\Live;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class LiveDTO extends BaseDTOAbstract
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
    public $start_time;
    public $end_time;
    protected string $imageFolder = 'live';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
