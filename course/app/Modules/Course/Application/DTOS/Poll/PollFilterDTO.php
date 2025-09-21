<?php

namespace App\Modules\Course\Application\DTOS\Poll;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class PollFilterDTO extends BaseDTOAbstract
{
    public $poll_id;
    public $content_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $image;
    public $is_fake;
    protected string $imageFolder = 'poll';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


}
