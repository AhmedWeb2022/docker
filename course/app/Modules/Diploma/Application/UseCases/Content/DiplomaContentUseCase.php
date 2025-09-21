<?php

namespace App\Modules\Diploma\Application\UseCases\Content;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Diploma\Application\DTOS\DiplomaContent\DiplomaContentDTO;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaContentRepository;
use App\Modules\Diploma\Http\Resources\Content\ContentResource;

class DiplomaContentUseCase
{
    protected $employee;
    protected $diplomaContentRepository;

    public function __construct()
    {
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
        $this->diplomaContentRepository = new DiplomaContentRepository();
    }

    public function addContents(DiplomaContentDTO $diplomaContentDTO): DataStatus
    {
        try {
            // dd($diplomaContentDTO);
            // $addContentDTO->created_by = $this->employee->id;
            // dd($addContentDTO);
            $content = $this->diplomaContentRepository->create($diplomaContentDTO);
            return DataSuccess(
                status: true,
                message: 'Contents created successfully',
                data: new ContentResource($content)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchContentDiplomas(DiplomaContentDTO $diplomaContentDTO): DataStatus
    {
        try {
            // dd($diplomaContentDTO);
            $contents = $this->diplomaContentRepository->filter($diplomaContentDTO);
            return DataSuccess(
                status: true,
                message: 'Contents fetched successfully',
                data: ContentResource::collection($contents)->response()->getData()
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    // public function updateContent(DiplomaContentDTO $diplomaContentDTO): DataStatus
    // {
    //     try {
    //         // $diplomaContentDTO->updated_by = $this->employee->id;
    //         $content = $this->diplomaContentRepository->update($diplomaContentDTO->content_id, $diplomaContentDTO);
    //         return DataSuccess(
    //             status: true,
    //             message: 'Content updated successfully',
    //             data: new ContentResource($content)
    //         );
    //     } catch (\Exception $e) {
    //         return DataFailed(
    //             status: false,
    //             message: $e->getMessage()
    //         );
    //     }
    // }

    public function deleteContent(DiplomaContentDTO $diplomaContentDTO): DataStatus
    {
        try {
            $this->diplomaContentRepository->delete($diplomaContentDTO->content_id);
            return DataSuccess(
                status: true,
                message: 'Content deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
