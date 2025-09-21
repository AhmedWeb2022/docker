<?php

namespace App\Modules\Course\Application\DTOS\LiveAnswer;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LiveAnswerFilterDTO extends BaseDTOAbstract
{
    public $live_answer_id;
    public $organization_id;
    public $content_id;
    public $live_question_id;
    public $image;
    public $answer;
    public $is_correct;
    public $attachments;
    protected string $imageFolder = 'live_answer';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
