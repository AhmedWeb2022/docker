<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\DiplomaAbout;

use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaAbout\DiplomaAbout;
use Exception;

class DiplomaAboutRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new DiplomaAbout());
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
