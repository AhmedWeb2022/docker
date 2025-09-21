<?php

namespace App\Modules\Course\Application\DTOS\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class CourseSettingDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $course_id;
    public $organization_id;
    public $code_status;
    public $is_security;
    public $is_watermark;
    public $is_voice;
    public $is_emulator;
    public $time_number;
    public $number_of_voice;
    public $watch_video;
    public $number_watch_video;
    public $setting_id;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    // public function uniqueAttributes(): array
    // {
    //     return [
    //         "course_id" => $this->course_id
    //     ]; // Default empty array
    // }
}
