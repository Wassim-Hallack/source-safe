<?php

namespace App\Http\Controllers;

use App\Http\Requests\File_add_Request;
use App\Http\Requests\File_destroy_Request;
use App\Http\Requests\File_edit_Request;
use App\Http\Requests\File_get_Request;
use App\Http\Requests\FileAddRequest;
use App\Http\Requests\FileDestroyRequest;
use App\Http\Requests\FileEditRequest;
use App\Http\Requests\FileGetRequest;
use App\Services\FileService;
use App\Traits\LogExecutionTrait;

class FileController extends Controller
{
    use LogExecutionTrait;

    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function get(File_get_Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->get($request);
        }, __FUNCTION__, $request->all());
    }

    public function add(File_add_Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->add($request);
        }, __FUNCTION__, $request->all());
    }

    public function edit(File_edit_Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->edit($request);
        }, __FUNCTION__, $request->all());
    }

    public function destroy(File_destroy_Request $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->destroy($request);
        }, __FUNCTION__, $request->all());
    }
}
