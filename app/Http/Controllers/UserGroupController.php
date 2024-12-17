<?php

namespace App\Http\Controllers;

use App\Services\UserGroupService;

class UserGroupController extends Controller
{
    protected UserGroupService $userGroupService;

    public function __construct(UserGroupService $userGroupService)
    {
        $this->userGroupService = $userGroupService;
    }
}
