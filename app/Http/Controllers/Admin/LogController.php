<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Log\GetLogRequest;
use App\Services\Admin\LogService;

class LogController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function get_log(GetLogRequest $request)
    {
        return $this->logService->get_log($request);
    }
}
