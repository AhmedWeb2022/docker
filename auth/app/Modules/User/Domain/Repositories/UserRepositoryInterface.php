<?php

namespace App\Modules\User\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\User\Application\DTOS\User\UserDTO;
use App\Modules\User\Application\DTOS\User\RegisterDTO;
use App\Modules\User\Application\DTOS\User\UserFilterDTO;
use App\Modules\Auth\Infrastructure\Persistence\Models\Auth\Auth;
use App\Modules\Auth\Infrastructure\Persistence\Models\User\User;

interface UserRepositoryInterface
{
    public function getAll($paginate = true, $limit = 10): Collection|LengthAwarePaginator;
    public function getById(int $id): User;
    public function create( $dto): User;
    public function update(int $id, UserDTO $UserDTO): User;
    public function delete(int $id): bool;
    public function filter(UserFilterDTO $UserDTO): Collection|LengthAwarePaginator;
}
