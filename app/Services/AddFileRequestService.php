<?php

namespace App\Services;

use App\Models\AddFileRequest;
use App\Repositories\AddFileRequestRepository;

class AddFileRequestService
{
    protected AddFileRequestRepository $addFileRequestRepository;

    public function __construct(AddFileRequestRepository $addFileRequestRepository)
    {
        $this->addFileRequestRepository = $addFileRequestRepository;
    }
}
