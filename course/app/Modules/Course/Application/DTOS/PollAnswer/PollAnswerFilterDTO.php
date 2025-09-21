<?php

namespace App\Modules\Course\Application\DTOS\PollAnswer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class PollAnswerFilterDTO extends BaseDTOAbstract
{
    public $poll_answer_id;
    public $poll_id;
    public $translations;
    public $organization_id;
    public $image;
    public $perecentage;
    protected string $imageFolder = 'poll_answer';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
