<?php

namespace App\Modules\Course\Application\DTOS\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class AddLevelDTO extends BaseDTOAbstract
{

    public $course_id;
    public $level_ids;
    public $level_id;
    public $price;
    public $payment_status;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'level_ids',
        ];
    }
}
