<?php
namespace App\Modules\Diploma\Application\DTOS\DiplomaContentCourse;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class DiplomaContentCourseDTO extends BaseDTOAbstract
{
    public $diploma_id;
    public $diploma_level_id;
    public $diploma_level_track_id;
    public $order;
    public $diploma_content_id;
    public $course_id;
    public $content_course_id;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    // public function handleSpecialCases() {
    //     $this->diploma_level_track_id = $this->track_id;
    // }
}
