<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Content;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class ContentRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Content());
    }

    public function filter(
        BaseDTOInterface $dto,
        string $operator = 'like',
        array $translatableFields = [],
        array $searchableFields = [],
        $paginate = false,
        $limit = 10,
        array $whereHasRelations = [],
        array $whereHasMultipleRelations = []
    ): Collection|LengthAwarePaginator {
        try {
            $query = $this->getModel()->query();
            // Apply parent_id condition
            // $query->when(
            //     isset($dto->parent_id) && $dto->parent_id !== null,
            //     fn($q) => $q->where('parent_id', $dto->parent_id),
            //     fn($q) => $q->whereNull('parent_id')
            // );

            // $query->when(isset($dto->course_id) && $dto->course_id !== null, function ($q) use ($dto) {
            //     $q->whereHas('lesson', function ($q) use ($dto) {
            //         $q->where('course_id', $dto->course_id);
            //     });
            // });



            // Apply other filters
            foreach ($dto->toArray() as $key => $value) {
                if (!in_array($key, ['lat', 'lng', 'distance']) && filled($value)) {
                    $query
                        ->when(in_array($key, $translatableFields), fn($q) => $q->whereTranslationLike($key, "%{$value}%"))
                        ->when(is_array($value), fn($q) => $q->whereIn($key, $value))
                        ->when(is_bool($value), fn($q) => $q->where($key, $value))
                        ->when(is_numeric($value), fn($q) => $q->where($key, $operator, $value))
                        ->when(
                            is_string($value) && !in_array($key, $translatableFields),
                            fn($q) => $q->where($key, $operator, $operator === 'like' ? "%{$value}%" : $value)
                        );
                }
            }
            // dd($query->get());
            foreach ($whereHasRelations as $relation => $conditions) {
                // dd($relation, $conditions);

                // dd($conditions);
                foreach ($conditions as $key => $values) {
                    if (is_array($values)) {
                        if ($values !== []) {
                            $query->whereHas($relation, function ($q) use ($key, $values) {
                                $q->whereIn($key, $values);
                            });
                        }
                    } else if (is_numeric($values)) {
                        if ($values !== null) {
                            $query->whereHas($relation, function ($q) use ($key, $values) {
                                $q->where($key, $values);
                            });
                        }
                    }
                }
            }
            // dd($query->get());


            return $paginate ? ($limit ? $query->paginate($limit) : $query->paginate(10)) : $query->get();
        } catch (Exception $e) {
            dd($e->getMessage());
            report($e);
            return collect();
        }
    }
}
