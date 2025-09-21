<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Content;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Http\Resources\Content\ContentResource;
use App\Modules\Course\Application\DTOS\Content\ContentFilterDTO;
use App\Modules\Course\Application\DTOS\Teacher\Content\ContentDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Content\ContentRepository;

class ContentUseCase
{

    protected $contentRepository;


    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function fetchContents(ContentDTO $contentDTO): DataStatus
    {
        try {
            // dd($contentDTO->toArray());
            $contents = $this->contentRepository->filter(
                $contentDTO,
                operator: 'like',
                translatableFields: ['title'],
                paginate: $contentDTO->paginate,
                limit: $contentDTO->limit,
                whereHasRelations: [
                    'live' => [
                        'group_id' => $contentDTO->group_id,
                        'teacher_id' => $contentDTO->teacher_id,
                    ],
                ]
            );
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $contents
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchContentsResource(ContentDTO $contentDTO): DataStatus
    {
        try {
            // dd($contentDTO->toArray());
            $contents = $this->contentRepository->filter(
                $contentDTO,
                operator: 'like',
                translatableFields: ['title'],
                paginate: $contentDTO->paginate,
                limit: $contentDTO->limit,
                whereHasRelations: [
                    'live' => [
                        'group_id' => $contentDTO->group_id,
                        'teacher_id' => $contentDTO->teacher_id,
                    ],
                ]
            );
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $contentDTO->paginate ? ContentResource::collection($contents)->response()->getData() : ContentResource::collection($contents),
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
