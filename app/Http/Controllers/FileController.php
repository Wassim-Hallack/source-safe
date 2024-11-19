<?php

namespace App\Http\Controllers;

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

    public function get(FileGetRequest $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->get($request);
        }, __FUNCTION__, $request->all());
    }

    public function add(FileAddRequest $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->add($request);
        }, __FUNCTION__, $request->all());
    }

    public function edit(FileEditRequest $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->edit($request);
        }, __FUNCTION__, $request->all());
    }

    public function destroy(FileDestroyRequest $request)
    {
        return $this->logExecution(function () use ($request) {
            return $this->fileService->destroy($request);
        }, __FUNCTION__, $request->all());
    }
}
