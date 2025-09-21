<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma;


use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack\DiplomaLevelTrack;
use Exception;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel\DiplomaLevel;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

class DiplomaLevelTrackRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new DiplomaLevelTrack());
    }

    public function deleteByLevelId(int $levelId): void
{
    $tracks = $this->getModel()::where('diploma_level_id', $levelId)->get();

    foreach ($tracks as $track) {
        app(DiplomaContentRepository::class)->deleteByTrackId($track->id);

        $track->delete();
    }
}

}
