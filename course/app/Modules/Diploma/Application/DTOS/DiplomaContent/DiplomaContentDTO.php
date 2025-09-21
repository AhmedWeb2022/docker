<?php
namespace App\Modules\Diploma\Application\DTOS\DiplomaContent;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class DiplomaContentDTO extends BaseDTOAbstract
{
    public $diploma_id;
    public $diploma_level_id;
    public $diploma_level_track_id;
    public $order;
    public $content_id;
    public $track_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases() {
        $this->diploma_level_track_id = $this->track_id;
    }
}
