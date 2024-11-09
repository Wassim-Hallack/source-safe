<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileAddRequest;
use App\Http\Requests\FileDestroyRequest;
use App\Http\Requests\FileEditRequest;
use App\Http\Requests\FileGetRequest;
use App\Models\File;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function get(FileGetRequest $request)
    {
        return $this->fileService->get($request);
    }

    public function add(FileAddRequest $request)
    {
        return $this->fileService->add($request);
    }

    public function edit(FileEditRequest $request)
    {
        return $this->fileService->edit($request);
    }

    public function destroy(FileDestroyRequest $request)
    {
        return $this->fileService->destroy($request);
    }
}
