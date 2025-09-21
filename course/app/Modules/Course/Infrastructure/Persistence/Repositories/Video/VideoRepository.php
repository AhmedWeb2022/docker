<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Video;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;

class VideoRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Video());
    }


}
