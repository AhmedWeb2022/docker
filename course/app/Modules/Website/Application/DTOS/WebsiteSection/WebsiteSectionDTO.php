<?php
namespace App\Modules\Website\Application\DTOS\WebsiteSection;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class WebsiteSectionDTO extends BaseDTOAbstract
{
    // protected bool $excludeAttributes = true;
    public $id;
    public $order;
    public $status;
    public $type;
    public $style;
    public $parent_id;
    public $image;
    public $is_active;
    public $translations;
    public $website_section_id;
    public $created_by;
    public $updated_by;
    public $attachments;
    public $contents;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
