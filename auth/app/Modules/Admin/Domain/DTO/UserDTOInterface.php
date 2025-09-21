<?php

namespace App\Modules\User\Domain\DTO;

interface UserDTOInterface
{

    public static function fromArray(array $data): self;

    public function toArray(): array;
}
