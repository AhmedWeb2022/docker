<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Group;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Group\GroupUseCase;
use App\Modules\Course\Http\Requests\Global\Group\GroupIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Group\FetchGroupRequest;
use App\Modules\Course\Http\Requests\Dashboard\Group\CreateGroupRequest;
use App\Modules\Course\Http\Requests\Dashboard\Group\UpdateGroupRequest;
use App\Modules\Course\Http\Requests\Dashboard\Group\HandelMultibleUsersRequest;

class GroupController extends Controller
{
    protected $groupUseCase;

    public function __construct(GroupUseCase $groupUseCase)
    {
        $this->groupUseCase = $groupUseCase;
    }

    public function fetchGroups(FetchGroupRequest $request)
    {
        return $this->groupUseCase->fetchGroups($request->toDTO())->response();
    }

    public function fetchGroupDetails(GroupIdRequest $request)
    {
        return $this->groupUseCase->fetchGroupDetails($request->toDTO())->response();
    }


    public function createGroup(CreateGroupRequest $request)
    {
        return $this->groupUseCase->createGroup($request->toDTO())->response();
    }

    public function updateGroup(UpdateGroupRequest $request)
    {
        return $this->groupUseCase->updateGroup($request->toDTO())->response();
    }


    public function deleteGroup(GroupIdRequest $request)
    {
        return $this->groupUseCase->deleteGroup($request->toDTO())->response();
    }

    public function addMultipleGroupUser(HandelMultibleUsersRequest $request)
    {
        return $this->groupUseCase->addMultipleGroupUser($request->toDTO())->response();
    }

    public function deleteMultipleGroupUser(HandelMultibleUsersRequest $request)
    {
        return $this->groupUseCase->deleteMultipleGroupUser($request->toDTO())->response();
    }
}
