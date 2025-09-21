<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Document;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Document\Document;

class DocumentRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Document());
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
            $query->when(
                isset($dto->parent_id) && $dto->parent_id !== null,
                fn($q) => $q->where('parent_id', $dto->parent_id),
                fn($q) => $q->whereNull('parent_id')
            );

            // Apply other filters
            foreach ($dto as $key => $value) {
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

            return $paginate ? $query->paginate($limit) : $query->get();
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }
}
