<?php

namespace App\Modules\Course\Http\Controllers\Api\SubscribedClient;

use App\Modules\Course\Application\UseCases\SubscribedClient\SubscribedClientUseCase;
use App\Modules\Course\Http\Requests\Global\SubscribedClient\SubscribedClientRequest;
use App\Modules\Course\Http\Requests\Api\SubscribedClient\CreateSubscribedClientRequest;


class SubscribedClientController
{
    protected $subscribedClientUseCase;
    public function __construct(SubscribedClientUseCase $subscribedClientUseCase)
    {
        $this->subscribedClientUseCase = $subscribedClientUseCase;
    }

    public function createSubscribedClient(CreateSubscribedClientRequest $request)
    {
        return $this->subscribedClientUseCase->createSubscribedClient($request->toDTO())->response();
    }
}
