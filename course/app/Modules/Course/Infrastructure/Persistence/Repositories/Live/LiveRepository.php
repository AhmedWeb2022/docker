<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Live;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Live\Live;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class LiveRepository extends BaseRepositoryAbstract
{
    protected $employeeApiService;
    public function __construct()
    {
        $this->setModel(new Live());
        $this->employeeApiService = new EmployeeApiService();
    }

    public function getTeachers($liveId)
    {
        try {
            $live = $this->getWhere('id', $liveId, 'first');

            if (!$live) {
                throw new \Exception('course not found.');
            }
            $teacherIds = $live->teacher_id;
            // dd($teacherIds);
            $response = $this->employeeApiService->fetchTeachers($teacherIds);
            if ((isset($response['success']) && !$response['success']) || (isset($response['status']) && !$response['status'])) {
                return null;
            }
            return $response['data'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
