<?php

namespace App\Services;

use App\Repositories\GroupInvitationRepository;

class GroupInvitationService
{
    protected $groupInvitationRepository;

    public function __construct(GroupInvitationRepository $groupInvitationRepository)
    {
        $this->groupInvitationRepository = $groupInvitationRepository;
    }
}
