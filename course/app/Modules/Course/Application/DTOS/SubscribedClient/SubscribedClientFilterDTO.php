<?php

namespace App\Modules\Course\Application\DTOS\SubscribedClient;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class SubscribedClientFilterDTO extends BaseDTOAbstract
{
    public $subscribed_client_id;
    public $organization_id;
    public $email;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
