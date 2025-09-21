<?php

namespace App\Modules\Course\Application\UseCases\Video;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Application\DTOS\Video\VideoFilterDTO;
use App\Modules\Course\Http\Resources\Video\FullVideoResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;

class VideoUseCase
{

    protected $videoRepository;



    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function fetchVideos(VideoFilterDTO $videoFilterDTO, $withChild = false): DataStatus
    {
        try {
            $videos = $this->videoRepository->filter($videoFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $withChild ? FullVideoResource::collection($videos) : VideoResource::collection($videos)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchVideoDetails(VideoFilterDTO $videoFilterDTO): DataStatus
    {
        try {
            $video = $this->videoRepository->getById($videoFilterDTO->video_id);
            return DataSuccess(
                status: true,
                message: 'Course Video fetched successfully',
                data: new VideoResource($video)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createVideo(VideoDTO $videoDTO): DataStatus
    {
        try {
            $video = $this->videoRepository->create($videoDTO);

            $video->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Video created successfully',
                data: new VideoResource($video)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateVideo(VideoDTO $videoDTO): DataStatus
    {
        try {
            $video = $this->videoRepository->update($videoDTO->video_id, $videoDTO);
            return DataSuccess(
                status: true,
                message: 'Course Video updated successfully',
                data: new VideoResource($video)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteVideo(VideoFilterDTO $videoFilterDTO): DataStatus
    {
        try {
            $video = $this->videoRepository->delete($videoFilterDTO->video_id);
            return DataSuccess(
                status: true,
                message: 'Course Video deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
