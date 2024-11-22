<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group_create_Request;
use App\Http\Requests\Group_users_in_group_Request;
use App\Http\Requests\Group_users_out_group_Request;
use App\Services\GroupService;
use App\Traits\LogExecutionTrait;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    use LogExecutionTrait;

    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function create(Group_create_Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->groupService->create($request);
        }, __FUNCTION__, $request->all());
    }

    public function get()
    {
        return $this->logExecution(function () {
            return $this->groupService->get();
        }, __FUNCTION__);
    }

    public function users_out_group(Group_users_out_group_Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->groupService->users_out_group($request);
        }, __FUNCTION__, $request->all());
    }

    public function users_in_group(Group_users_in_group_Request $request)
    {
        return $this->groupService->users_in_group($request);
    }

    public function invite_member(Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->groupService->invite_member($request);
        }, __FUNCTION__, $request->all());
    }
}
