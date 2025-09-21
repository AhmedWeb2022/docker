<?php

namespace App\Modules\Course\Application\DTOS\Teacher\Review;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class ReviewDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;

    public $review_id;
    public $teacher_id;
    public $content_id;
    public $user_id;
    public $follow_up;
    public $degree_focus;
    public $interacting_tasks;
    public $behavior_cooperation;
    public $progress_understanding;
    public $notes;


    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
