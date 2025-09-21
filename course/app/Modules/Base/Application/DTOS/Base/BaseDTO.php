<?php

namespace App\Modules\Base\Application\DTOS\Base;

use App\Modules\Base\Domain\DTO\BaseDTOInterface;

class BaseDTO implements BaseDTOInterface
{
    public function __construct(
        public ?int $base_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            base_id: $data['base_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'base_id' => $this->base_id,
        ];
    }
}
