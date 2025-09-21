<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma;


use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent\DiplomaContent;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack\DiplomaLevelTrack;
use Exception;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel\DiplomaLevel;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

class DiplomaContentRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new DiplomaContent());
    }
    public function deleteByTrackId(int $trackId): void
{
    $contents = $this->getModel()::where('diploma_level_track_id', $trackId)->get();

    foreach ($contents as $content) {
        app(DiplomaContentCourseRepository::class)->deleteByContentId($content->id);

        $content->delete();
    }
}

public function deleteByLevelId(int $levelId): void
{
    $contents = $this->getModel()::where('diploma_level_id', $levelId)->get();

    foreach ($contents as $content) {
        app(DiplomaContentCourseRepository::class)->deleteByContentId($content->id);
        $content->delete();
    }
}

}
