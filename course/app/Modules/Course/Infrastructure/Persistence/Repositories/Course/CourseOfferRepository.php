<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Course;


use App\Modules\Course\Infrastructure\Persistence\Models\CourseOffer\CourseOffer;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;


class CourseOfferRepository extends BaseRepositoryAbstract
{


    public function __construct()
    {
        $this->setModel(new CourseOffer());
    }

}
