<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\DiplomaTarget;


use Exception;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaTarget\DiplomaTarget;

class DiplomaTargetRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new DiplomaTarget());
    }

    public function deleteByDiplomaId(int $diplomaId): bool
    {
        try {
            $this->getModel()::where('diploma_id', $diplomaId)->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
