<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\CreateRequest;
use App\Http\Requests\Group\UsersInGroupRequest;
use App\Http\Requests\Group\UsersOutGroupRequest;
use App\Services\GroupService;
use App\Traits\LogExecutionTrait;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    use LogExecutionTrait;

    protected GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function create(CreateRequest $request)
    {
        return $this->groupService->create($request);
    }

    public function get()
    {
        return $this->groupService->get();
    }

    public function users_out_group(UsersOutGroupRequest $request)
    {
        return $this->groupService->users_out_group($request);
    }

    public function users_in_group(UsersInGroupRequest $request)
    {
        return $this->groupService->users_in_group($request);
    }
}
