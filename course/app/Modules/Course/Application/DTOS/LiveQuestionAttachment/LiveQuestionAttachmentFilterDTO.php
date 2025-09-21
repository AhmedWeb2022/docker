<?php

namespace App\Modules\Course\Application\DTOS\LiveQuestionAttachment;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LiveQuestionAttachmentFilterDTO extends BaseDTOAbstract
{
    public $live_question_attachment_id;
    public $organization_id;
    public $live_question_id;
    public $media;
    public $type;
    public $alt;
    protected string $imageFolder = 'live_question_attachment';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
