<?php

namespace App\Modules\Course\Application\DTOS\CourseDependency;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CoursePaymentDTO extends BaseDTOAbstract
{
    public $course_id;
    public $is_paid;
    public $price;
    public $currency_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    
}
