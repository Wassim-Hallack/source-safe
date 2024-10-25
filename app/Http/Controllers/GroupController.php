<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupFile;
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function create(Request $request)
    {
        return $this->groupService->create($request);
    }

    public function get()
    {
        return $this->groupService->get();
    }

    public function users_out_group(Request $request) {
        return $this->groupService->users_out_group($request);
    }

    public function invite_member(Request $request) {
        return $this->groupService->invite_member($request);
    }
}
