<?php

namespace App\Modules\Course\Application\UseCases\Session;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Session\SessionDTO;
use App\Modules\Course\Http\Resources\Session\SessionResource;
use App\Modules\Course\Application\DTOS\Session\SessionFilterDTO;
use App\Modules\Course\Http\Resources\Session\FullSessionResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Session\SessionRepository;

class SessionUseCase
{

    protected $sessionRepository;



    public function __construct()
    {
        $this->sessionRepository = new SessionRepository();
    }

    public function fetchSessions(SessionFilterDTO $sessionFilterDTO, $withChild = false): DataStatus
    {
        try {
            $sessions = $this->sessionRepository->filter($sessionFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $withChild ? FullSessionResource::collection($sessions) : SessionResource::collection($sessions)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchSessionDetails(SessionFilterDTO $sessionFilterDTO): DataStatus
    {
        try {
            $session = $this->sessionRepository->getById($sessionFilterDTO->session_id);
            return DataSuccess(
                status: true,
                message: 'Course Session fetched successfully',
                data: new SessionResource($session)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createSession(SessionDTO $sessionDTO): DataStatus
    {
        try {
            $session = $this->sessionRepository->create($sessionDTO);

            $session->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Session created successfully',
                data: $session
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateSession(SessionDTO $sessionDTO): DataStatus
    {
        try {
            $session = $this->sessionRepository->update($sessionDTO->session_id, $sessionDTO);
            return DataSuccess(
                status: true,
                message: 'Course Session updated successfully',
                data: $session
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteSession(SessionFilterDTO $sessionFilterDTO): DataStatus
    {
        try {
            $session = $this->sessionRepository->delete($sessionFilterDTO->session_id);
            return DataSuccess(
                status: true,
                message: 'Course Session deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
