<?php

namespace App\Modules\Course\Application\DTOS\LiveQuestion;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LiveQuestionFilterDTO extends BaseDTOAbstract
{
        public $live_question_id;
    public $organization_id;
    public $content_id;
    public $parent_id;
    public $question_type;
    public $identicality;
    public $identicality_percentage;
    public $difficulty;
    public $difficulty_level;
    public $question;
    public $degree;
    public $time;
    public $creator;
    public $attachments;
    protected string $imageFolder = 'live_question';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
