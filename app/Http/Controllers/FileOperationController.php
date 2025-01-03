<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileOperation\exportUserOperationsRequest;
use App\Http\Requests\FileOperation\ExportFileOperationsRequest;
use App\Http\Requests\FileOperation\GetFileOperationsRequest;
use App\Http\Requests\FileOperation\GetUserOperationsRequest;
use App\Models\FileOperation;
use App\Services\FileOperationService;
use Illuminate\Http\Request;

class FileOperationController extends Controller
{
    protected FileOperationService $fileOperationService;

    public function __construct(FileOperationService $fileOperationService)
    {
        $this->fileOperationService = $fileOperationService;
    }

    public function get_file_operations(GetFileOperationsRequest $request)
    {
        return $this->fileOperationService->get_file_operations($request);
    }

    public function get_user_operations(GetUserOperationsRequest $request)
    {
        return $this->fileOperationService->get_user_operations($request);
    }

    public function export_file_operations(ExportFileOperationsRequest $request)
    {
        return $this->fileOperationService->export_file_operations($request);
    }

    public function export_user_operations(ExportUserOperationsRequest $request)
    {
        return $this->fileOperationService->export_user_operations($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FileOperation $fileOperation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileOperation $fileOperation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileOperation $fileOperation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileOperation $fileOperation)
    {
        //
    }
}
