<?php

namespace App\Modules\Employee\Application\DTOS\Social;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;

class SocialDTO extends BaseDTOAbstract
{
    public $employee_id;
    protected bool $hasMorph = true;
    protected array $morphMap = [
        'employee_id' => Employee::class,
    ];
    public  $social_id;
    public  $facebook;
    public  $twitter;
    public  $instagram;
    public  $linkedin;
    public  $tiktok;
    public  $whatsapp;
    public $socialable_id; // This is the morphable id
    public $socialable_type; // This is the morphable type
    protected string $imageFolder = 'social'; // fallback folder
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function getMorphFields($data)
    {
        if ($this->morphMap == []) {
            throw new \Exception("Morph map is not defined in " . get_class($this));
        } else {
            // dd($this->morphMap);
            foreach ($data as $key => $value) {
                if (isset($this->morphMap[$key])) {
                    $this->socialable_id = $value;
                    $this->socialable_type = $this->morphMap[$key];

                    return [
                        'socialable_id' => $this->socialable_id,
                        'socialable_type' => $this->socialable_type,
                    ];
                }
            }
        }
        return [];
    }

        public function excludedAttributes(): array
    {
        return [
            'employee_id',
        ]; // Default empty array
    }
}
