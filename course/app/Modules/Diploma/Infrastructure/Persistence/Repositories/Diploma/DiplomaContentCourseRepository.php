<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma;


use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent\DiplomaContent;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContentCourse\DiplomaContentCourse;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack\DiplomaLevelTrack;
use Exception;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel\DiplomaLevel;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

class DiplomaContentCourseRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new DiplomaContentCourse());
    }
    
    public function deleteByContentId(int $contentId): void
{
    $this->getModel()::where('diploma_content_id', $contentId)->delete();
}

}
