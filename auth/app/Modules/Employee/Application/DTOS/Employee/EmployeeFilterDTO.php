<?php

namespace App\Modules\Employee\Application\DTOS\Employee;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class EmployeeFilterDTO extends BaseDTOAbstract
{
    public  $id;
    public  $name;
    public  $email;
    public $role;
    public $token;
    public $word;
    public $subject_stage_id;
    public $has_course;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases()
    {
        $token = request()->bearerToken();
        if ($token) {
            $this->token = $token;
        } else {
            $this->token = null;
        }
    }
    public function excludedAttributes(): array
    {
        return [
            'token',
            'word',
            'subject_stage_id',
            'has_course'
        ]; // Default empty array
    }
}
