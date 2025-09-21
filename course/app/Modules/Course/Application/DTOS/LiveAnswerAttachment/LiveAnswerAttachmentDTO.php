<?php

namespace App\Modules\Course\Application\DTOS\LiveAnswerAttachment;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LiveAnswerAttachmentDTO extends BaseDTOAbstract
{
    public $live_answer_attachment_id;
    public $organization_id;
    public $live_answer_id;
    public $media;
    public $type;
    public $alt;
    protected string $imageFolder = 'live_answer_attachment';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
