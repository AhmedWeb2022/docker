<?php
namespace App\Modules\Diploma\Application\DTOS\Diploma;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class DiplomaLevelDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $image;
    public $has_track;
    public $diploma_id;
    public $diploma_level_ids;
    public $translations;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function excludedAttributes(): array
    {
        return [
            'diploma_level_ids',
        ];
    }
}
