<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Employee\Infrastructure\Persistence\ApiService\StageApiService;

class EmployeeDTO extends BaseDTOAbstract
{
    public  $employee_id;
    public $translations;
    public  $name;
    public  $username;
    public  $email;
    public  $phone;
    public  $password;
    public  $identifyNumber;
    public $image;
    public $cover_image;
    public $real_video;
    public $role;
    public $socials; // This will hold the social data
    public $certificates;
    public $subject_stage_ids;
    protected string $imageFolder = 'employee'; // fallback folder
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }



    public function excludedAttributes(): array
    {
        return [
            'subject_stage_ids',
        ]; // Default empty array
    }
}
