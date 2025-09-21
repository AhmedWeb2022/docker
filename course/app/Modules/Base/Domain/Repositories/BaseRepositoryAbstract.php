<?php

namespace App\Modules\Base\Domain\Repositories;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Mod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class BaseRepositoryAbstract implements BaseRepositoryInterface
{
    private Model $model;

    /**
     * Set the model instance dynamically
     */
    public function setModel(Model $model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get the current model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Retrieve all records with optional pagination
     */
    public function getAll(bool $paginate = true, int $limit = 10): Collection|LengthAwarePaginator
    {
        try {
            return $paginate ? $this->model->paginate($limit) : $this->model->all();
        } catch (Exception $e) {
            report($e);
            return collect(); // Return an empty collection in case of failure
        }
    }

    /**
     * Retrieve a record by ID
     */
    public function getById(int $id): Model
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            report($e);
            return new $this->model(); // Return a new instance of the model in case of failure
        }
    }

    /**
     * Create a new record
     */
    public function create(BaseDTOInterface $dto): Model
    {
        try {
            DB::beginTransaction();
            $model = $this->model->create($dto->toArray());
            DB::commit();
            $model->refresh();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            deleteFilesFromDto($dto);
            report($e);
            return new $this->model(); // Return a new instance of the model in case of failure
        }
    }
    public function attachPivot(string $relationMethod, array|int $data): bool
    {
        try {

            $relation = $this->model->{$relationMethod}();
            if (!method_exists($relation, 'attach')) {
                throw new \Exception("Method {$relationMethod} is not a valid many-to-many relation.");
            }

            $relation->attach($data); // Accepts ID, array of IDs, or associative array for pivot
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
    public function syncPivot(string $relationMethod, array|int $data, bool $detaching = true): bool
    {
        try {
            $relation = $this->model->{$relationMethod}();
            if (!method_exists($relation, 'sync')) {
                throw new \Exception("Method {$relationMethod} is not a valid many-to-many relation.");
            }

            $relation->sync($data, $detaching); // Will replace existing unless $detaching is false
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
    public function detachPivot(string $relationMethod, array|int|null $ids = null): bool
    {
        try {
            $relation = $this->model->{$relationMethod}();
            if (!method_exists($relation, 'detach')) {
                throw new \Exception("Method {$relationMethod} is not a valid many-to-many relation.");
            }

            $relation->detach($ids); // Can be null (detach all), ID, or array of IDs
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function updateOrCreate(BaseDTOInterface $dto): Model
    {
        try {
            DB::beginTransaction();

            // Get unique attributes from DTO
            $uniqueAttributes = method_exists($dto, 'uniqueAttributes')
                ? $dto->uniqueAttributes()
                : [];
            //            dd($uniqueAttributes);
            if (!empty($uniqueAttributes)) {
                // throw new Exception('Unique attributes not defined for updateOrCreate.');
                $uniqueValues = collect($uniqueAttributes)
                    ->mapWithKeys(fn($key) => [$key => $dto->$key ?? null])
                    ->toArray();
                // dd($uniqueValues);
                $model = $this->model->updateOrCreate(
                    $uniqueValues,
                    $dto->toArray()
                );
            } else {

                $model = $this->model->updateOrCreate(
                    $uniqueAttributes,
                    $dto->toArray()
                );
            }
            // dd($uniqueAttributes , $dto->toArray());

            DB::commit();
            $model->refresh();
            return $model;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            deleteFilesFromDto($dto);
            report($e);
            return new $this->model(); // Return a new instance of the model in case of failure
        }
    }



    /**
     * Update an existing record by ID
     */
    public function update(int $id, BaseDTOInterface $dto): Model
    {
        try {
            DB::beginTransaction();
            $record = $this->model->findOrFail($id);
            deleteOldFiles($dto, $record);
            $record->update($dto->toArray());
            DB::commit();
            $record->refresh();
            return $record;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            deleteFilesFromDto($dto);
            report($e);
            return new $this->model();
        } catch (Exception $e) {
            DB::rollBack();
            deleteFilesFromDto($dto);
            report($e);
            return new $this->model();
        }
    }

    /**
     * Update order of an existing record by ID
     */
    public function updateOrder(int $id, BaseDTOInterface $dto): Model
    {
        try {
            DB::beginTransaction();
            $newOrder = $dto->order;
            if (!isset($newOrder) || !is_numeric($newOrder)) {
                throw new \Exception('Order value is required and must be numeric.', Response::HTTP_BAD_REQUEST);
            }
            $record = $this->model->findOrFail($id);

            // If new order is greater than max, assign it to max
            Log::info('newOrder', ['newOrder' => $newOrder, 'maxOrder' => $this->model->max('order')]);
            Log::info('all_orders', [$this->model->where('order', '>=', $newOrder)->select('id', 'order')->get()->toArray()]);
            $maxOrder = $this->model->max('order');
            $newOrder = max(1, min($newOrder, $maxOrder));

            // Move other records down
            // $this->model->where('order', '>=', $newOrder)
            //     ->where('id', '!=', $id)
            //     ->increment('order');
            foreach ($this->model->where('order', '>=', $newOrder)->where('id', '!=', $id)->get() as $key => $value) {
                $value->update(['order' => $value->order + 1]);
            }

            // Move the current record to the correct position
            $record->update(['order' => $newOrder]);
            DB::commit();
            $record->refresh();
            return $record;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            report($e);
            return new $this->model();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return new $this->model();
        }
    }

    public function toggle(array $where, BaseDTOInterface $dto)
    {
        try {
            DB::beginTransaction();
            // dd($where);
            $record = $this->getMultibleWhere($where, 'first');
            // dd($record);
            if (!$record) {
                $record = $this->model->create($dto->toArray());
                DB::commit();
                $record->refresh();
                return $record;
            }
            $record->delete();
            DB::commit();
            $record->refresh();
            return $record;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            deleteFilesFromDto($dto);
            report($e);
            return new $this->model();
        } catch (Exception $e) {
            DB::rollBack();
            deleteFilesFromDto($dto);
            report($e);
            return new $this->model();
        }
    }

    public function toggleColumn(array $where, string $column)
    {
        try {
            DB::beginTransaction();

            $record = $this->getMultibleWhere($where, 'first');

            if (!$record) {
                throw new ModelNotFoundException("Record not found for toggle.");
            }

            $record->$column = $record->$column == 1 ? 0 : 1;
            $record->save();

            DB::commit();

            $record->refresh();
            return $record;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            report($e);
            return new $this->model();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return new $this->model();
        }
    }


    /**
     * Delete a record by ID
     */
    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();
            $record = $this->model->findOrFail($id);
            deleteRecordFiles($record);
            $record->delete();
            DB::commit();
            return true;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            report($e);
            return false;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }

    /**
     * Check if a record exists based on a key-value pair
     */
    public function checkExist(string $key, mixed $value): bool
    {
        try {
            return $this->model->where($key, $value)->exists();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Retrieve records where a key matches a value
     */
    public function getWhere(string $key, mixed $value, $type = 'get'): Collection|Model
    {
        try {
            $model = $this->model->where($key, $value)->$type();
            if ($type === 'get' && $model->isEmpty()) {
                return new $this->model();
            } elseif ($type === 'first' && $model == null) {
                return new $this->model();
            }
            return $model;
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }

    public function getWhereIn(string $key, array $value, $type = 'get'): Collection|Model
    {
        try {
            $model = $this->model->whereIn($key, $value)->$type();
            if ($type === 'get' && $model->isEmpty()) {
                return new $this->model();
            } elseif ($type === 'first' && $model == null) {
                return new $this->model();
            }
            return $model;
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }


    public function getWhereFirst(string $key, mixed $value): ?Model
    {
        try {
            return $this->model->where($key, $value)->first();
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    public function getWhereLast(string $key, mixed $value): ?Model
    {
        try {
            return $this->model->where($key, $value)->latest()->first();
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }


    public function getMultibleWhere(array $where, $type = 'get'): Collection|Model|null
    {
        try {
            // dd($where);
            $query = $this->model->query();
            foreach ($where as $key => $value) {
                // dd($key, $value);
                if (is_array($value)) {
                    $query->whereIn($key, $value);
                } else {
                    // Log::info(['key' => $key, 'value' => $value]);
                    $query->where($key, $value);
                }
            }
            // Log::info($query->$type());
            return $query->$type();
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }

    /**
     * Filter records dynamically, supporting translations and array values
     */
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
            $query = $this->model->query();
            if (!empty($dto->word)) {
                if ($translatableFields != []) {
                    $query->where(function ($q) use ($dto, $translatableFields) {
                        foreach ($translatableFields as $field) {
                            $q->orWhereTranslationLike($field, "%{$dto->word}%");
                        }
                    });
                }
                if ($searchableFields != []) {

                    $query->where(function ($q) use ($dto, $searchableFields) {
                        foreach ($searchableFields as $field) {
                            $q->orWhere($field, 'like', "%{$dto->word}%");
                        }
                    });
                }
            }
            foreach ($dto->toArray() as $key => $value) {
                // dd($key, $value);
                if (!in_array($key, ['lat', 'lng', 'distance']) && filled($value)) {
                    $query
                        ->when(in_array($key, $translatableFields), fn($q) => $q->whereTranslationLike($key, "%{$value}%"))
                        ->when(is_array($value), fn($q) => $q->whereIn($key, $value))
                        ->when(is_bool($value), fn($q) => $q->where($key, $value))
                        ->when(is_numeric($value), fn($q) => $q->where($key, $operator, $value));
                    // ->when(is_string($value) && !in_array($key, $translatableFields), fn($q) => $q->where($key, $operator, ($operator === 'like' ? "%{$value}%" : $value)));
                }
                // dd($query->get());
            }
            // Apply whereHas relations with whereIn
            foreach ($whereHasRelations as $relation => $conditions) {

                // Case 1: If relation is passed as a string with no conditions
                if (is_int($relation) && is_string($conditions)) {
                    $query->whereHas($conditions);
                    continue;
                }

                // Case 2: If relation conditions is a closure
                if (is_callable($conditions)) {
                    $query->whereHas($relation, $conditions);
                    continue;
                }

                // Case 3: Default array-based logic (your original code)
                if (is_array($conditions)) {
                    foreach ($conditions as $key => $values) {
                        if (is_array($values)) {
                            if ($values !== []) {
                                $query->whereHas($relation, function ($q) use ($key, $values) {
                                    $q->whereIn($key, $values);
                                });
                            }
                        } elseif (is_numeric($values)) {
                            if ($values !== null) {
                                $query->whereHas($relation, function ($q) use ($key, $values) {
                                    $q->where($key, $values);
                                });
                            }
                        }
                    }
                }
            }

            // dd($query->get());

            // Apply whereHasMultiple relations with whereIn
            foreach ($whereHasMultipleRelations as $relationsGroup) {
                foreach ($relationsGroup as $relation => $conditions) {
                    $query->whereHas($relation, function ($q) use ($conditions) {
                        foreach ($conditions as $key => $values) {
                            if (is_array($values)) {
                                $q->whereIn($key, $values); // Use whereIn for arrays
                            } else {
                                $q->where($key, $values); // Use where for single values
                            }
                        }
                    });
                }
            }
            // Order by nearest location if lat & lng exist
            $query->when(isset($dto->lat) && isset($dto->lng), function ($query) use ($dto) {
                $query->select('*', DB::raw("(
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(lat)) *
                        cos(radians(lng) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(lat))
                    )
                ) AS distance", [$dto->lat, $dto->lng, $dto->lat]))
                    ->orderBy('distance', 'asc');
            })->when(isset($dto->lat) && isset($dto->lng) && isset($dto->distance), function ($query) use ($dto) {
                $query->select('*', DB::raw("(
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(lat)) *
                        cos(radians(lng) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(lat))
                    )
                ) AS distance", [$dto->lat, $dto->lng, $dto->lat]))
                    ->having('distance', '<=', $dto->distance)
                    ->orderBy('distance', 'asc');
            });

            return $paginate ? $query->orderBy($dto->orderBy, $dto->direction)->paginate($limit) : $query->orderBy($dto->orderBy, $dto->direction)->get();
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }

    public function getWhereHas(string $relation, ?string $key = null, $value = null,  $type = 'get', $paginate = false, $limit = 10, ?string $orderBy = null, string $direction = 'asc', array $keys = [], array $values = []): Collection|LengthAwarePaginator
    {
        try {
            $query = $this->model->query();
            if ($keys && $values) {
                $query->whereHas($relation, function ($q) use ($keys, $values) {
                    foreach ($keys as $index => $key) {
                        $q->where($key, $values[$index]);
                    }
                });
            } else {
                $query->whereHas($relation, function ($q) use ($key, $value) {
                    $q->where($key, $value);
                });
            }

            if ($orderBy) {
                $query->orderBy($orderBy, $direction);
            }
            return $paginate ? ($limit ? $query->paginate($limit) : $query->paginate(10)) : $query->get();
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }

    public function getWhereHasMultiple(array $relations, string $type = 'get', bool $paginate = false, int $limit = 10): Collection|LengthAwarePaginator
    {
        try {
            $query = $this->model->query();

            foreach ($relations as $relation => $conditions) {
                $query->whereHas($relation, function ($q) use ($conditions) {
                    foreach ($conditions as $key => $value) {
                        if (is_array($value)) {
                            $q->where($key, $value[0], $value[1]); // e.g., ['age', '>', 18]
                        } else {
                            $q->where($key, $value); // e.g., ['name' => 'John']
                        }
                    }
                });
            }

            return $paginate ? ($limit ? $query->paginate($limit) : $query->paginate(10)) : $query->get();
        } catch (\Exception $e) {
            report($e);
            return collect();
        }
    }
}
