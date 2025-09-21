<?php

namespace App\Modules\Diploma\Application\DTOS\Faq;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;


class FaqDTO extends BaseDTOAbstract
{
    protected string $question_en ;
    protected string $question_ar ;
    protected string $answer_en ;
    protected string $answer_ar ;
    public  $faq_id ;
    protected int $per_page ;
    protected string $word ;
    protected int $order;
    public $updated_by;
    public $created_by;
    public $diploma_id;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
