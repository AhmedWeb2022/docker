<?php

namespace App\Modules\Course\Application\UseCases\Group;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Group\GroupDTO;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Http\Resources\Group\GroupResource;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Application\DTOS\Group\GroupFilterDTO;
use App\Modules\Course\Http\Resources\Group\FullGroupResource;
use App\Modules\Course\Application\DTOS\GroupUser\GroupUserDTO;
use App\Modules\Course\Http\Resources\Group\Api\GroupCourseResource;
use App\Modules\Course\Http\Resources\Group\GroupWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;
use App\Modules\Course\Http\Resources\Group\GroupWithChildrenResource;
use App\Modules\Course\Http\Resources\Group\Api\GroupDetailsFullResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Group\GroupRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\GroupUser\GroupUserRepository;

class GroupUseCase
{

    protected $groupRepository;
    protected $groupUserRepository;
    protected $employee;


    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->groupUserRepository = new GroupUserRepository();
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchGroups(GroupFilterDTO $groupFilterDTO): DataStatus
    {
        try {
            $groups = $this->groupRepository->filter(
                dto: $groupFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $groupFilterDTO->paginate,
                limit: $groupFilterDTO->limit
            );
            $resource =  GroupResource::collection($groups);
            return DataSuccess(
                status: true,
                message: 'Groups fetched successfully',
                data: $groupFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchGroupDetails(GroupFilterDTO $groupFilterDTO, $viewType = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $group = $this->groupRepository->getById($groupFilterDTO->group_id);
            // dd($group->certificates);
            $resource = $viewType == ViewTypeEnum::WEBSITE->value ? new GroupDetailsFullResource($group) : new GroupResource($group);
            return DataSuccess(
                status: true,
                message: 'Group fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createGroup(GroupDTO $groupDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $group = $this->groupRepository->create($groupDTO);
            if ($group) {
                if (isset($groupDTO->student_ids) && count($groupDTO->student_ids) > 0) {
                    foreach ($groupDTO->student_ids as $student_id) {
                        $groupUserDTO = GroupUserDTO::fromArray([
                            'group_id' => $group->id,
                            'user_id' => $student_id
                        ]);
                        $this->groupUserRepository->create($groupUserDTO);
                    }
                }
            }
            DB::commit();
            return DataSuccess(
                status: true,
                message: 'Group created successfully',
                data: new GroupResource($group)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateGroup(GroupDTO $groupDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $group = $this->groupRepository->update($groupDTO->group_id, $groupDTO);
            DB::commit();
            return DataSuccess(
                status: true,
                message: ' Group updated successfully',
                data: new GroupResource($group)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteGroup(GroupFilterDTO $groupFilterDTO): DataStatus
    {
        try {
            $group = $this->groupRepository->delete($groupFilterDTO->group_id);
            return DataSuccess(
                status: true,
                message: ' Group deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchGroupCourses(GroupFilterDTO $groupFilterDTO, $viewType = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $group = $this->groupRepository->getById($groupFilterDTO->group_id);
            if (!$group) {
                return DataFailed(
                    status: false,
                    message: 'Group not found'
                );
            }

            return DataSuccess(
                status: true,
                message: 'Group courses fetched successfully',
                data: CourseResource::collection($group->courses)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteMultipleGroupUser(GroupFilterDTO $groupFilterDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            if ((isset($groupFilterDTO->student_ids) && count($groupFilterDTO->student_ids) > 0) && (isset($groupFilterDTO->group_id) && $groupFilterDTO->group_id != null)) {
                foreach ($groupFilterDTO->student_ids as $student_id) {
                    $groupUser = $this->groupUserRepository->getMultibleWhere([
                        'group_id' => $groupFilterDTO->group_id,
                        'user_id' => $student_id
                    ], 'first');
                    $this->groupUserRepository->delete($groupUser->id);
                }
            }
            DB::commit();


            return DataSuccess(
                status: true,
                message: ' Group deleted successfully',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function addMultipleGroupUser(GroupFilterDTO $groupFilterDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            if ((isset($groupFilterDTO->student_ids) && count($groupFilterDTO->student_ids) > 0) && (isset($groupFilterDTO->group_id) && $groupFilterDTO->group_id != null)) {
                foreach ($groupFilterDTO->student_ids as $student_id) {
                    $old = $this->groupUserRepository->getMultibleWhere([
                        'group_id' => $groupFilterDTO->group_id,
                        'user_id' => $student_id
                    ], 'first');
                    if (!$old) {

                        $groupUserDTO = GroupUserDTO::fromArray([
                            'group_id' => $groupFilterDTO->group_id,
                            'user_id' => $student_id
                        ]);
                        $this->groupUserRepository->create($groupUserDTO);
                    }
                }
            }
            DB::commit();
            return DataSuccess(
                status: true,
                message: ' Group students added successfully',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function handelGroupViewResource($groups, $view)
    {
        if ($view == ViewTypeEnum::DASHBOARD->value) {
            return  GroupResource::collection($groups);
        } elseif ($view == ViewTypeEnum::WEBSITE->value) {
            return  GroupResource::collection($groups);
        } else {
            return  GroupResource::collection($groups);
        }
    }
}
