<?php

namespace App\Modules\Course\Application\DTOS\Audio;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class AudioFilterDTO extends BaseDTOAbstract
{
    public $audio_id;
    public $content_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $can_skip;
    public $skip_rate;
    public $status;
    public $image;
    public $audio;
    public $link;
    public $file;
    public $audio_type;
    public $is_file;
    protected string $imageFolder = 'audio';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
