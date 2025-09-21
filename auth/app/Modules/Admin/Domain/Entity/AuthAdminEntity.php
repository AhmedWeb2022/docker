<?php

namespace App\Modules\Admin\Domain\Entity;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Base\Domain\Entity\AuthEntityAbstract;

class AuthAdminEntity extends AuthEntityAbstract
{
    public function __construct(
        public int $id,
        public string $name,
        public Model $model,
        public string $email,
        public string $password,
        public string $phone,
        public string $status,
        public array $data = []
    ) {
        parent::__construct(id: $id, name: $name, email: $email, password: $password, phone: $phone, status: $status, model: $model, data: $data);
    }
}
