<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Currency;


use App\Modules\Course\Infrastructure\Persistence\Models\Currency\Currency;


use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

class CurrencyRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Currency());
    }
}
