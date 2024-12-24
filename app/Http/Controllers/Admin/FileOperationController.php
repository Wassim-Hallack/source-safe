<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileOperation\ExportAllUserOperationsRequest;
use App\Http\Requests\Admin\FileOperation\GetAllUserOperationsRequest;
use App\Services\Admin\FileOperationService;

class FileOperationController extends Controller
{
    protected FileOperationService $fileOperationService;

    public function __construct(FileOperationService $fileOperationService)
    {
        $this->fileOperationService = $fileOperationService;
    }

    public function get_all_user_operations(GetAllUserOperationsRequest $request)
    {
        return $this->fileOperationService->get_all_user_operations($request);
    }

    public function export_all_user_operations(ExportAllUserOperationsRequest $request) {
        return $this->fileOperationService->export_all_user_operations($request);
    }
}
