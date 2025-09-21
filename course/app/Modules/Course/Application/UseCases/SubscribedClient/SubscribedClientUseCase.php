<?php

namespace App\Modules\Course\Application\UseCases\SubscribedClient;


use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\SubscribedClient\SubscribedClientDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\SubscribedClient\SubscribedClientRepository;



class SubscribedClientUseCase
{

    protected $subscribedClientRepository;


    public function __construct(SubscribedClientRepository $subscribedClientRepository)
    {
        $this->subscribedClientRepository = $subscribedClientRepository;
    }

        public function createSubscribedClient(SubscribedClientDTO $subscribedClientDTO): DataStatus
    {
        try {
            $SubscribedClient = $this->subscribedClientRepository->create($subscribedClientDTO);

            return DataSuccess(
                status: true,
                message: 'SubscribedClient created successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
