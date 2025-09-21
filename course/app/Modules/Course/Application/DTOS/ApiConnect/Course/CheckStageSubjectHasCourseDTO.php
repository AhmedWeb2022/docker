<?php

namespace App\Modules\Course\Application\DTOS\ApiConnect\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CheckStageSubjectHasCourseDTO extends BaseDTOAbstract
{

    public $subject_stage_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'subject_stage_id',
        ];
    }
}
