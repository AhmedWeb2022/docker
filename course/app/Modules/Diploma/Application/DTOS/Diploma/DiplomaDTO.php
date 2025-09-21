<?php
namespace App\Modules\Diploma\Application\DTOS\Diploma;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class DiplomaDTO extends BaseDTOAbstract
{
    // protected bool $excludeAttributes = true;
    public $main_image;
    public $has_level;
    public $has_track;
    public $start_date;
    public $end_date;
    public $target;
    public $number_of_courses;
    public $translations;
    public $diploma_id;
    public $created_by;
    public $updated_by;
    public $level_ids;
    public $language;
    public $diploma_specialization;
    public $targets;
    public $abouts;
    public $id;
    public $levels;
    public $tracks;
    public $order;
    public $is_active;
    public string $imageFolder = 'diplomas';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
