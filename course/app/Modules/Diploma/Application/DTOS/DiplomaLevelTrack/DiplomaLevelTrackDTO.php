<?php
namespace App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class DiplomaLevelTrackDTO extends BaseDTOAbstract
{
    public $diploma_id;
    public $track_id;
    public $tracks;
    public $diploma_level_id;
    public $translations;
    public $courses_ids;
    public $order;
    public $is_active;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function excludedAttributes(): array
    {
        return [
            'tracks',
            'courses_ids',
        ];
    }
}