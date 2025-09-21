<?php

namespace App\Modules\Course\Application\UseCases\Audio;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Audio\AudioDTO;
use App\Modules\Course\Http\Resources\Audio\AudioResource;
use App\Modules\Course\Application\DTOS\Audio\AudioFilterDTO;
use App\Modules\Course\Http\Resources\Audio\FullAudioResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Audio\AudioRepository;

class AudioUseCase
{

    protected $audioRepository;



    public function __construct()
    {
        $this->audioRepository = new AudioRepository();
    }

    public function fetchAudios(AudioFilterDTO $audioFilterDTO, $withChild = false): DataStatus
    {
        try {
            $audios = $this->audioRepository->filter($audioFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $withChild ? FullAudioResource::collection($audios) : AudioResource::collection($audios)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchAudioDetails(AudioFilterDTO $audioFilterDTO): DataStatus
    {
        try {
            $audio = $this->audioRepository->getById($audioFilterDTO->audio_id);
            return DataSuccess(
                status: true,
                message: 'Course Audio fetched successfully',
                data: new AudioResource($audio)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createAudio(AudioDTO $audioDTO): DataStatus
    {
        try {
            $audio = $this->audioRepository->create($audioDTO);

            $audio->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Audio created successfully',
                data: $audio
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateAudio(AudioDTO $audioDTO): DataStatus
    {
        try {
            $audio = $this->audioRepository->update($audioDTO->audio_id, $audioDTO);
            return DataSuccess(
                status: true,
                message: 'Course Audio updated successfully',
                data: $audio
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteAudio(AudioFilterDTO $audioFilterDTO): DataStatus
    {
        try {
            $audio = $this->audioRepository->delete($audioFilterDTO->audio_id);
            return DataSuccess(
                status: true,
                message: 'Course Audio deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
