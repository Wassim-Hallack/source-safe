<?php

namespace App\Services;

use App\Repositories\UserGroupRepository;

class UserGroupService
{
    protected $userGroupRepository;

    public function __construct(UserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }
}
