<?php

namespace App\Http\Controllers;

use App\Services\UserGroupService;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    protected $userGroupService;

    public function __construct(UserGroupService $userGroupService)
    {
        $this->userGroupService = $userGroupService;
    }
}
