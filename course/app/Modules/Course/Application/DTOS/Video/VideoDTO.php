<?php

namespace App\Modules\Course\Application\DTOS\Video;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;

class VideoDTO extends BaseDTOAbstract
{
    public $video_id;
    public $videoable_id;
    public $videoable_type;
    public $link;
    public $file;
    public $is_file;
    public $video_type;
    public $path;
    protected string $imageFolder = 'video';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
