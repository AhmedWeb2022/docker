<?php

namespace App\Modules\Course\Application\DTOS\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;


class CourseDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $course_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $word;
    public $is_free;
    // public $subject_id;
    public $certificate_id;
    public $partner_id;
    public $type;
    public $status;
    public $is_private;
    public $has_website;
    public $has_app;
    public $start_date;
    public $end_date;
    public $image;
    public $video;
    public $created_by;
    public $updated_by;
    public $setting;
    public $platforms;
    public $payment;
    public $contain_live;
    public $is_certificate;
    public $course_type;
    public $education_type;
    public $level_type;
    public $teacherIds;
    public $subject_stage_ids;
    public $with_lessons;
    public $has_discount;
    public $lesson_id;
    protected string $imageFolder = 'course';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


    public function excludedAttributes(): array
    {
        return [
            'file',
            'video_type',
            'is_file',
            'setting',
            'payment',
            'subject_stage_ids',
            'teacherIds',
        ];
    }
}
