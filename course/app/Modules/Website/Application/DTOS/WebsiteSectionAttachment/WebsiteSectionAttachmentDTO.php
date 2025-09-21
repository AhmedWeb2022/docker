<?php
namespace App\Modules\Website\Application\DTOS\WebsiteSectionAttachment;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class WebsiteSectionAttachmentDTO extends BaseDTOAbstract
{
    // protected bool $excludeAttributes = true;
    public $id;
    public $website_section_id;
    public $file;
    public $link;
    public $alt;
    public $created_by;
    public $updated_by;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
