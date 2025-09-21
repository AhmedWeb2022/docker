<?php

namespace App\Modules\Course\Application\DTOS\Currency;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CurrencyFilterDTO extends BaseDTOAbstract
{

    public $word;
    public $code;
    public $limit;
    public $paginate;
    public $translations;
    public $currency_id;
    public $codes;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function excludedAttributes(): array
    {
        return [
            'word',
        ]; // Default empty array
    }
}
