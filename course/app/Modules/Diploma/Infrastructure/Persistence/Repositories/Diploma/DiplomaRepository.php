<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;


class DiplomaRepository extends BaseRepositoryAbstract
{

    protected $stageApiService;
    protected $employeeApiService;
    public function __construct()
    {
        $this->setModel(new Diploma());
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
        $query = $this->getModel()->newQuery();

        // Filter by translatable fields
        foreach ($translatableFields as $field) {
            if (!empty($dto->$field)) {
                $query->where($field, $operator, "%{$dto->$field}%");
            }
        }

        foreach ($dto->toArray() as $key => $value) {
            if (!in_array($key, ['lat', 'lng', 'distance']) && filled($value)) {
                $query
                    ->when(in_array($key, $translatableFields), fn($q) => $q->whereTranslationLike($key, "%{$value}%"))
                    ->when(is_array($value), fn($q) => $q->whereIn($key, $value))
                    ->when(is_bool($value), fn($q) => $q->where($key, $value))
                    ->when(is_numeric($value), fn($q) => $q->where($key, $operator, $value))
                    ->when(is_string($value) && !in_array($key, $translatableFields), fn($q) => $q->where($key, $operator, ($operator === 'like' ? "%{$value}%" : $value)));
            }
        }

        // Additional filters from DTO
        if (!empty($dto->created_by)) {
            $query->where('created_by', $dto->created_by);
        }
        if (!empty($dto->level_id)) {
            $query->whereHas('levels', function ($q) use ($dto) {
                $q->where('id', $dto->level_id);
            });
        }
        // Add more filters as needed...

        // Relations filtering
        foreach ($whereHasRelations as $relation => $conditions) {
            $query->whereHas($relation, function ($q) use ($conditions) {
                foreach ($conditions as $field => $value) {
                    $q->where($field, $value);
                }
            });
        }

        // Limit or paginate
        return $paginate ? $query->orderBy($dto->orderBy, $dto->direction)->paginate($limit) : $query->orderBy($dto->orderBy, $dto->direction)->get();
        if ($paginate) {
            return $query->orderBy($dto->orderBy, $dto->direction)->paginate($limit ?? 15);
        } elseif ($limit) {
            return $query->orderBy($dto->orderBy, $dto->direction)->limit($limit)->get();
        } else {
            return $query->orderBy($dto->orderBy, $dto->direction)->get();
        }
    }
}
