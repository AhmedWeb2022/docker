<?php

namespace App\Modules\Course\Application\DTOS\PollAnswer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class PollAnswerDTO extends BaseDTOAbstract
{
    public $poll_answer_id;
    public $poll_id;
    public $translations;
    public $organization_id;
    public $image;
    public $percentage;
    protected string $imageFolder = 'poll_answer';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
