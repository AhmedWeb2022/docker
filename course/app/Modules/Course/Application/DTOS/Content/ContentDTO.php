<?php

namespace App\Modules\Course\Application\DTOS\Content;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class ContentDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;

    public $content_id;
    public $lesson_id;
    public $course_id;
    public $level_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $type;
    public $status;
    public $image;
    public $content;
    public $created_by;
    public $updated_by;
    public $referances;
    public $order;
    public $can_skip;
    public $skip_rate;
    public $group_id;
    protected string $imageFolder = 'content';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }


    public function excludedAttributes(): array
    {
        return [
            'content',
        ];
    }
}
