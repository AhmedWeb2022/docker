<?php

namespace App\Modules\Course\Application\DTOS\Referance;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ReferanceDTO extends BaseDTOAbstract
{
    // protected bool $hasMorph = true;
    public $referance_id;
    public $translations;
    public $organization_id;
    public $image;
    public $link;
    public $referancable_type;
    public $referancable_id;

    protected string $imageFolder = 'referance';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    // public function getMorphFields($data)
    // {
    //     return [
    //         'referancable_type' => $data['referancable_type'],
    //         'referancable_id' => $data['referancable_id'],
    //     ]; // Default empty array
    // }
}
