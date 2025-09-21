<?php

namespace App\Modules\Course\Application\DTOS\Content;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ContentFilterDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $content_id;
    public $course_id;
    public $type;
    public $lesson_id;
    public $group_id;
    public $parent_id;
    protected string $imageFolder = 'content';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'group_id',
        ];
    }
}
