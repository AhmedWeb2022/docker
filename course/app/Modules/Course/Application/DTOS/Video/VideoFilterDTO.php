<?php

namespace App\Modules\Course\Application\DTOS\Video;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class VideoFilterDTO extends BaseDTOAbstract
{
    public $video_id;
    public $course_id;
    public $type;
    public $video_type;
    public $video;
    public $path;
    protected string $imageFolder = 'video';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
