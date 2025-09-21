<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Repositories\EmployeeSubjectStage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Modules\Base\Domain\ApiService\WhatsAppApiService;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Employee\Application\DTOS\Certificate\CertificateDTO;
use App\Modules\Employee\Infrastructure\Persistence\Models\EmployeeSubjectStage\EmployeeSubjectStage;
use App\Modules\Employee\Infrastructure\Persistence\ApiService\CourseApiService;

class EmployeeSubjectStageRepository extends BaseRepositoryAbstract
{

    public function __construct()
    {
        $this->setModel(new EmployeeSubjectStage());
    }
}
