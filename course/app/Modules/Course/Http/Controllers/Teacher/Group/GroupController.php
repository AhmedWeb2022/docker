<?php

namespace App\Modules\Course\Http\Controllers\Teacher\Group;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Teacher\Group\GroupUseCase;
use App\Modules\Course\Http\Requests\Teacher\Group\GroupIdRequest;
use App\Modules\Course\Http\Requests\Teacher\Group\FetchGroupRequest;
use App\Modules\Course\Http\Requests\Teacher\Group\CreateGroupRequest;
use App\Modules\Course\Http\Requests\Teacher\Group\UpdateGroupRequest;
use App\Modules\Course\Http\Requests\Teacher\Group\HandelMultibleUsersRequest;

class GroupController extends Controller
{
    protected $groupUseCase;

    public function __construct(GroupUseCase $groupUseCase)
    {
        $this->groupUseCase = $groupUseCase;
    }

    public function fetchGroups(FetchGroupRequest $request)
    {
        return $this->groupUseCase->fetchResourceGroups($request->toDTO())->response();
    }

    public function fetchGroupDetails(GroupIdRequest $request)
    {
        return $this->groupUseCase->fetchGroupDetails($request->toDTO())->response();
    }
}
