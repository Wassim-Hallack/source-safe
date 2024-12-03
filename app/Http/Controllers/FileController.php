<?php

namespace App\Http\Controllers;

use App\Http\Requests\File_add_Request;
use App\Http\Requests\File_check_in_Request;
use App\Http\Requests\File_destroy_Request;
use App\Http\Requests\File_download_Request;
use App\Http\Requests\File_download_version_Request;
use App\Http\Requests\File_edit_Request;
use App\Http\Requests\File_get_file_versions_Request;
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
        return $this->fileService->get($request);
    }

    public function add(File_add_Request $request)
    {
        return $this->fileService->add($request);
    }

    public function edit(File_edit_Request $request)
    {
        return $this->fileService->edit($request);
    }

    public function destroy(File_destroy_Request $request)
    {
        return $this->fileService->destroy($request);
    }

    public function check_in(File_check_in_Request $request)
    {
        return $this->fileService->check_in($request);
    }

    public function download(File_download_Request $request)
    {
        return $this->fileService->download($request);
    }

    public function get_file_versions(File_get_file_versions_Request $request)
    {
        return $this->fileService->get_file_versions($request);
    }

    public function download_version(File_download_version_Request $request)
    {
        return $this->fileService->download_version($request);
    }
}
