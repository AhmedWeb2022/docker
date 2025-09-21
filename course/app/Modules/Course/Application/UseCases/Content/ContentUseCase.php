<?php

namespace App\Modules\Course\Application\UseCases\Content;

use Dom\Document;
use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Live\LiveDTO;
use App\Modules\Course\Application\DTOS\Poll\PollDTO;
use App\Modules\Course\Application\DTOS\Audio\AudioDTO;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Content\ContentDTO;
use App\Modules\Course\Application\DTOS\Session\SessionDTO;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\DTOS\Document\DocumentDTO;
use App\Modules\Course\Http\Resources\Content\ContentResource;
use App\Modules\Course\Application\DTOS\Referance\ReferanceDTO;
use App\Modules\Course\Application\DTOS\Content\ContentFilterDTO;
use App\Modules\Course\Application\DTOS\LiveAnswer\LiveAnswerDTO;
use App\Modules\Course\Application\DTOS\PollAnswer\PollAnswerDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Http\Resources\Content\FullContentResource;
use App\Modules\Course\Application\DTOS\LiveQuestion\LiveQuestionDTO;
use App\Modules\Course\Infrastructure\Persistence\Models\Audio\Audio;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Live\LiveRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Poll\PollRepository;
use App\Modules\Course\Application\DTOS\LiveAnswerAttachment\LiveAnswerAttachmentDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Audio\AudioRepository;
use App\Modules\Course\Application\DTOS\LiveQuestionAttachment\LiveQuestionAttachmentDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Content\ContentRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Session\SessionRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Document\DocumentRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Referance\ReferanceRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswer\LiveAnswerRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\PollAnswer\PollAnswerRepository;
use App\Modules\Course\Http\Resources\Content\Website\ContentResource as WebsiteContentResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveQuestion\LiveQuestionRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswerAttachment\LiveAnswerAttachmentRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveQuestionAttachment\LiveQuestionAttachmentRepository;

class ContentUseCase
{

    protected $contentRepository;
    protected $sessionRepository;
    protected $audioRepository;
    protected $documentRepository;
    protected $pollRepository;
    protected $pollAnswerRepository;
    protected $referanceRepository;
    protected $liveRepository;
    protected $liveQuestionRepository;
    protected $liveAnswerRepository;
    protected $liveAnswerAttachmentRepository;
    protected $liveQuestionAttachmentRepository;
    protected $employee;


    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
        $this->sessionRepository = new SessionRepository();
        $this->audioRepository = new AudioRepository();
        $this->documentRepository = new DocumentRepository();
        $this->referanceRepository = new ReferanceRepository();
        $this->pollRepository = new PollRepository();
        $this->pollAnswerRepository = new PollAnswerRepository();
        $this->liveRepository = new LiveRepository();
        $this->liveQuestionRepository = new LiveQuestionRepository();
        $this->liveAnswerRepository = new LiveAnswerRepository();
        $this->liveAnswerAttachmentRepository = new LiveAnswerAttachmentRepository();
        $this->liveQuestionAttachmentRepository = new LiveQuestionAttachmentRepository();
    }

    public function fetchContents(ContentDTO $contentDTO, $withChild = false): DataStatus
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
                    ]
                ]
            );
            return DataSuccess(
                status: true,
                message: 'Contents fetched successfully',
                data: $contentDTO->paginate ? ContentResource::collection($contents)->response()->getData() : ContentResource::collection($contents),
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchContentDetails(ContentDTO $contentDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $content = $this->contentRepository->getById($contentDTO->content_id);
            return DataSuccess(
                status: true,
                message: 'Course Content fetched successfully',
                data: $view == ViewTypeEnum::DASHBOARD->value ? new ContentResource($content) : new WebsiteContentResource($content)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createContent(ContentDTO $contentDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $content = $this->contentRepository->create($contentDTO);

            $content->refresh();
            DB::commit();
            return DataSuccess(
                status: true,
                message: 'Course Content created successfully',
                data: $content
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateContent(ContentDTO $contentDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $content = $this->contentRepository->update($contentDTO->content_id, $contentDTO);
            $content->refresh();
            DB::commit();
            return DataSuccess(
                status: true,
                message: 'Course Content updated successfully',
                data: $content
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function updateContentOrder(ContentDTO $contentDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
            $contentDTO->updated_by = $this->employee->id;
            $content = $this->contentRepository->updateOrder($contentDTO->content_id, $contentDTO);
            DB::commit();
            return DataSuccess(
                status: true,
                message: 'Course Content updated successfully',
                data: new ContentResource($content)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function deleteContent(ContentFilterDTO $contentFilterDTO): DataStatus
    {
        try {
            $content = $this->contentRepository->delete($contentFilterDTO->content_id);
            return DataSuccess(
                status: true,
                message: 'Course Content deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    private function storeReferances($model, $referances)
    {

        foreach ($referances as $referance) {
            $referenceDTO = ReferanceDTO::fromArray([
                'translations' => $referance['translations'],
                'link' => $referance['link'],
                'referancable_id' => $model->id,
                'referancable_type' => get_class($model),
            ]);
            $this->referanceRepository->create($referenceDTO);
        }
    }

    private function updateReferances($model, $referances)
    {
        foreach ($referances as $referance) {
            $referenceDTO = ReferanceDTO::fromArray([
                'translations' => $referance['translations'],
                'link' => $referance['link'],
                'referancable_id' => $model->id,
                'referancable_type' => get_class($model),
            ]);
            $this->referanceRepository->update($referance['referance_id'], $referenceDTO);
        }
    }
}
