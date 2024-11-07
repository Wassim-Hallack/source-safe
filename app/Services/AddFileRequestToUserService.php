<?php

namespace App\Services;

use App\Repositories\AddFileRequestToUserRepository;

class AddFileRequestToUserService
{
    protected AddFileRequestToUserRepository $addFileRequestToUserRepository;

    public function __construct(AddFileRequestToUserRepository $addFileRequestToUserRepository)
    {
        $this->addFileRequestToUserRepository = $addFileRequestToUserRepository;
    }
}
