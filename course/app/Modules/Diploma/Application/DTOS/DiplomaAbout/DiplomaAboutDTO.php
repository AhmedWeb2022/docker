<?php

namespace App\Modules\Diploma\Application\DTOS\DiplomaAbout;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class DiplomaAboutDTO extends BaseDTOAbstract
{
    public $diploma_about_id;
    public $diploma_id;
    public $translations;
    public $is_active;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}