<?php

namespace App\Modules\Course\Application\DTOS\Session;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class SessionFilterDTO extends BaseDTOAbstract
{
    public $session_id;
    public $content_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $status;
    public $can_skip;
    public $skip_rate;
    public $image;
    public $session;
    public $link;
    public $file;
    public $session_type;
    public $is_file;
    protected string $imageFolder = 'session';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


}
