<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\AddRequest;
use App\Http\Requests\File\CheckinRequest;
use App\Http\Requests\File\DestroyRequest;
use App\Http\Requests\File\DownloadRequest;
use App\Http\Requests\File\DownloadVersionRequest;
use App\Http\Requests\File\EditRequest;
use App\Http\Requests\File\GetFileVersionsRequest;
use App\Http\Requests\File\GetRequest;
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

    public function get(GetRequest $request)
    {
        return $this->fileService->get($request);
    }

    public function add(AddRequest $request)
    {
        return $this->fileService->add($request);
    }

    public function edit(EditRequest $request)
    {
        return $this->fileService->edit($request);
    }

    public function destroy(DestroyRequest $request)
    {
        return $this->fileService->destroy($request);
    }

    public function check_in(CheckinRequest $request)
    {
        return $this->fileService->check_in($request);
    }

    public function download(DownloadRequest $request)
    {
        return $this->fileService->download($request);
    }

    public function get_file_versions(GetFileVersionsRequest $request)
    {
        return $this->fileService->get_file_versions($request);
    }

    public function download_version(DownloadVersionRequest $request)
    {
        return $this->fileService->download_version($request);
    }
}
