<?php

namespace App\Modules\Course\Application\UseCases\ContentView;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\ContentView\ContentViewDTO;
use App\Modules\Course\Http\Resources\ContentView\Api\ContentViewResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\ContentView\ContentViewRepository;

class ContentViewUseCase
{

    protected $contentViewRepository;
    protected $employee;


    public function __construct(ContentViewRepository $contentViewRepository)
    {
        $this->contentViewRepository = $contentViewRepository;
    }

    public function createContentView(ContentViewDTO $contentViewDTO): DataStatus
    {
        try {
            $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
            DB::beginTransaction();
            $contentViewDTO->user_id = $user->id;
            $contentView = $this->contentViewRepository->updateOrCreate($contentViewDTO);

            DB::commit();
            return DataSuccess(
                status: true,
                message: 'You view content successfully',
                data: new ContentViewResource($contentView)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
