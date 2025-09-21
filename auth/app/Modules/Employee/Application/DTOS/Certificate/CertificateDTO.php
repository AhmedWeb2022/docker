<?php

namespace App\Modules\Employee\Application\DTOS\Certificate;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CertificateDTO extends BaseDTOAbstract
{
    protected bool $hasMorph = true; // Default is false
    protected array $morphMap = [
        'employee_id' => 'App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee',
    ];
    public $employee_id;
    public $certificate_id;
    public $translations;
    public $organization_id;
    public $image;
    public $partner_id;
    public $is_website;
    public $cerifictable_id;
    public $cerifictable_type;
    public $token;
    protected string $imageFolder = 'certificate';
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
                    $this->cerifictable_id = $value;
                    $this->cerifictable_type = $this->morphMap[$key];

                    return [
                        'cerifictable_id' => $this->cerifictable_id,
                        'cerifictable_type' => $this->cerifictable_type,
                    ];
                }
            }
        }
        return [];
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
            'token'
        ]; // Default empty array
    }
}
