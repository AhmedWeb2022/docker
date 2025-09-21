<?php

namespace App\Modules\Course\Application\DTOS\CourseDependency;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CoursePlatformDTO extends BaseDTOAbstract
{
    public $platform_id;
    public $course_id;
    public $image;
    public $cover;
    public $link;
    public $slug;
    protected string $imageFolder = 'course_platform';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
