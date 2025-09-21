<?php

namespace App\Modules\Website\Application\UseCases\WebsiteSection;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Website\Application\DTOS\WebsiteSection\WebsiteSectionDTO;
use App\Modules\Website\Application\DTOS\WebsiteSectionAttachment\WebsiteSectionAttachmentDTO;
use App\Modules\Website\Application\DTOS\WebsiteSectionContent\WebsiteSectionContentDTO;
use App\Modules\Website\Http\Resources\WebsiteSection\WebsiteSectionDetailsResource;
use App\Modules\Website\Http\Resources\WebsiteSection\WebsiteSectionResource;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionAttachment\WebsiteSectionAttachment;
use App\Modules\Website\Infrastructure\Persistence\Repositories\WebsiteSection\WebsiteSectionRepository;
use App\Modules\Website\Infrastructure\Persistence\Repositories\WebsiteSectionAttachment\WebsiteSectionAttachmentRepository;
use App\Modules\Website\Infrastructure\Persistence\Repositories\WebsiteSectionContent\WebsiteSectionContentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebsiteSectionUseCase
{
    protected $employee;
    protected $websiteSectionRepository;
    protected $websiteSectionAttachmentRepository;
    protected $websiteSectionContentRepository;


    public function __construct()
    {
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
        $this->websiteSectionRepository = new WebsiteSectionRepository();
        $this->websiteSectionAttachmentRepository = new WebsiteSectionAttachmentRepository();
        $this->websiteSectionContentRepository = new WebsiteSectionContentRepository();
    }

    public function addWebsiteSections(WebsiteSectionDTO $websiteSectionDTO): DataStatus
    {

        try {
            DB::beginTransaction();
            // dd($websiteSectionDTO->contents );
            $websiteSection = $this->websiteSectionRepository->create($websiteSectionDTO);
            if (!empty($websiteSectionDTO->attachments) && is_array($websiteSectionDTO->attachments)) {
                // dd($websiteSectionDTO->attachments);
                foreach ($websiteSectionDTO->attachments as $attachment) {
                    /**
                     * @var WebsiteSectionAttachmentDTO $websiteSectionAttachmentDTO
                     */
                    $websiteSectionAttachmentDTO = WebsiteSectionAttachmentDTO::fromArray($attachment);
                    $websiteSectionAttachmentDTO->website_section_id = $websiteSection->id;
                    // dd($websiteSectionAttachmentDTO);
                    $this->websiteSectionAttachmentRepository->create($websiteSectionAttachmentDTO);
                }
            }
            if (!empty($websiteSectionDTO->contents) && is_array($websiteSectionDTO->contents)) {
                foreach ($websiteSectionDTO->contents as $content) {
                        // Log::info(['content' => $content]);
                    /**
                     * @var WebsiteSectionContentDTO $websiteSectionContentDTO
                     */
                    $websiteSectionContentDTO = WebsiteSectionContentDTO::fromArray($content);
                    $websiteSectionContentDTO->website_section_id = $websiteSection->id;
                    // dd($websiteSectionContentDTO);
                    $this->websiteSectionContentRepository->create($websiteSectionContentDTO);
                }
            }
            DB::commit();

            return DataSuccess(
                status: true,
                message: 'WebsiteSection and attachments created successfully',
                data: new WebsiteSectionResource($websiteSection)
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchWebsiteSection(WebsiteSectionDTO $WebsiteSectionDTO): DataStatus
    {
        try {
            // dd($diplomaWebsiteSectionDTO);
            $websiteSections = $this->websiteSectionRepository->filter($WebsiteSectionDTO);
            return DataSuccess(
                status: true,
                message: 'WebsiteSections fetched successfully',
                data: WebsiteSectionDetailsResource::collection($websiteSections)->response()->getData()
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

        public function fetchWebsiteSectionDetails(WebsiteSectionDTO $WebsiteSectionDTO): DataStatus
    {
        try {
            // dd($diplomaWebsiteSectionDTO);
            $websiteSections = $this->websiteSectionRepository->getById($WebsiteSectionDTO->website_section_id);
            return DataSuccess(
                status: true,
                message: 'WebsiteSections fetched successfully',
                data: WebsiteSectionDetailsResource::make($websiteSections)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateWebsiteSection(WebsiteSectionDTO $WebsiteSectionDTO): DataStatus
    {
        try {
            // $diplomaWebsiteSectionDTO->updated_by = $this->employee->id;
            $websiteSection = $this->websiteSectionRepository->update($WebsiteSectionDTO->website_section_id, $WebsiteSectionDTO);
            return DataSuccess(
                status: true,
                message: 'WebsiteSection updated successfully',
                data: new WebsiteSectionResource($websiteSection)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    // public function deleteWebsiteSection(WebsiteSectionDTO $WebsiteSectionDTO): DataStatus
    // {
    //     try {
    //         $this->websiteSectionRepository->delete($WebsiteSectionDTO->websiteSection_id);
    //         return DataSuccess(
    //             status: true,
    //             message: 'WebsiteSection deleted successfully'
    //         );
    //     } catch (\Exception $e) {
    //         return DataFailed(
    //             status: false,
    //             message: $e->getMessage()
    //         );
    //     }
    // }
}
