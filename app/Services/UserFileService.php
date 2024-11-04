<?php

namespace App\Services;

use App\Repositories\UserFileRepository;

class UserFileService
{
    protected UserFileRepository $userFileRepository;

    public function __construct(UserFileRepository $userFileRepository)
    {
        $this->userFileRepository = $userFileRepository;
    }
}
