<?php

namespace App\Modules\Course\Application\DTOS\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CourseFilterDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $course_id;
    public $title;
    public $description;
    public $word;
    public $price_from;
    public $price_to;
    public $parent_id;
    public $organization_id;
    public $has_hidden;
    public $has_favourite;
    public $teacher_id;
    public $certificate_id;
    public $is_certificate;
    public $partner_id;
    public $user_id;
    public $mine;
    public $type;
    public $with_lessons;
    public $status;
    public $limit;
    public $paginate;
    public $created_by;
    public $level_type;
    public $teacherIds;
    public $rate;
    public $has_discount;
    public $is_free;
    protected string $imageFolder = 'course';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'with_lessons',
            'excludeAttributes',
            'limit',
            'paginate',
            'word',
            'price_from',
            'price_to',
            'teacherIds',
            'teacher_id',
            'rate',
        ]; // Default empty array
    }
}
