<?php

namespace App\Modules\Course\Application\DTOS\Currency;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CurrencyDTO extends BaseDTOAbstract
{
    public $currency_id;
    public $translations;
    public $code;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
