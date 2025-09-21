<?php

namespace App\Modules\Course\Application\DTOS\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class CourseOfferDTO extends BaseDTOAbstract
{

    public $course_id;
    public $course_offer_id;
    public $discount_amount;
    public $discount_from_date;
    public $discount_to_date;
    public $created_by;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
