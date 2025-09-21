<?php

namespace App\Modules\Course\Application\DTOS\Document;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class DocumentDTO extends BaseDTOAbstract
{
    public $document_id;
    public $content_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $can_skip;
    public $skip_rate;
    public $status;
    public $image;
    public $link;
    public $file;
    public $document_type;
    public $is_file;
    protected string $imageFolder = 'document';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
