<?php

namespace App\Modules\Diploma\Application\DTOS\Faq;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class FaqFilterDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $faq_id;
    public $question_en;
    public $question_ar;
    public $answer_en;
    public $answer_ar;
    public $word;
    public $status;
    public $limit;
    public $paginate;
    public $created_by;
    public $diploma_id;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'excludeAttributes',
            'limit',
            'paginate',
            'word'
        ]; // Default empty array
    }
}
