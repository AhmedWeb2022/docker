<?php

namespace App\Modules\Course\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\UseCases\Group\GroupUseCase;
use App\Modules\Course\Http\Requests\Global\Group\GroupIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Group\FetchGroupRequest;

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
        return $this->groupUseCase->fetchGroupDetails($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }

    public function fetchGroupCourses(GroupIdRequest $request)
    {
        return $this->groupUseCase->fetchGroupCourses($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }
}
