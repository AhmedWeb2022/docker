<?php

namespace App\Modules\Employee\Application\UseCases\Social;





use App\Modules\Base\Application\Response\DataStatus;

use App\Modules\Employee\Application\DTOS\Social\SocialDTO;
use App\Modules\Employee\Http\Resources\Social\SocialResource;
use App\Modules\Employee\Infrastructure\Persistence\Repositories\Social\SocialRepository;

class SocialUseCase
{

    protected $socialRepository;
    /**
     *  @var Employee
     */

    protected $employee;


    public function __construct()
    {
        $this->socialRepository = new SocialRepository();
        // $this->employee = EmployeeHolder::getEmployee();
    }


    public function createSocial(SocialDTO $socialDTO): DataStatus
    {
        try {
            // dd($SocialDTO);
            $social = $this->socialRepository->create($socialDTO);
            // dd($Social);
            return DataSuccess(
                status: true,
                message: 'success',
                data: $social
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateSocial(SocialDTO $socialDTO): DataStatus
    {
        try {
            $social = $this->socialRepository->update($socialDTO->social_id, $socialDTO);

            return DataSuccess(
                status: true,
                message: 'success',
                data: new SocialResource($social)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteSocial(SocialDTO $socialDTO): DataStatus
    {
        try {
            $social = $this->socialRepository->delete($socialDTO->social_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

}
