<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Group;


use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\DTOS\Teacher\Group\GroupDTO;
use App\Modules\Course\Http\Resources\Teacher\Group\GroupResource;
use App\Modules\Course\Http\Resources\Group\Api\GroupDetailsFullResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Group\GroupRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\GroupUser\GroupUserRepository;

class GroupUseCase
{

    protected $groupRepository;
    protected $groupUserRepository;


    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->groupUserRepository = new GroupUserRepository();
    }

    public function fetchGroups(GroupDTO $groupDTO): DataStatus
    {
        try {
            $groups = $this->groupRepository->filter(
                dto: $groupDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $groupDTO->paginate,
                limit: $groupDTO->limit
            );
            return DataSuccess(
                status: true,
                message: 'Groups fetched successfully',
                data: $groups
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchGroupDetails(GroupDTO $groupDTO): DataStatus
    {
        try {
            $group = $this->groupRepository->getById($groupDTO->group_id);

            return DataSuccess(
                status: true,
                message: 'Group fetched successfully',
                data: $group
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

        public function fetchResourceGroups(GroupDTO $groupDTO): DataStatus
    {
        try {
            $groups = $this->groupRepository->filter(
                dto: $groupDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $groupDTO->paginate,
                limit: $groupDTO->limit
            );
            return DataSuccess(
                status: true,
                message: 'Groups fetched successfully',
                data: GroupResource::collection($groups)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
