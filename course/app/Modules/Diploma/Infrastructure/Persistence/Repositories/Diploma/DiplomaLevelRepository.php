<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma;


use Exception;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel\DiplomaLevel;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

class DiplomaLevelRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new DiplomaLevel());
    }

    /* public function createTracks(array $tracks, int $levelId): array
    {
        $createdTracks = [];
        foreach ($tracks as $track) {
            $createdTracks[] = $this->createTrack($track, $levelId);
        }
        return $createdTracks;
    } */

}
