<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFileRequest_get_Request;
use App\Http\Requests\AddFileRequest_response_Request;
use App\Models\AddFileRequest;
use App\Repositories\AddFileRequestRepository;
use App\Services\AddFileRequestService;
use Illuminate\Http\Request;

class AddFileRequestController extends Controller
{
    protected AddFileRequestService $addFileRequestService;

    public function __construct(AddFileRequestService $addFileRequestService)
    {
        $this->addFileRequestService = $addFileRequestService;
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
    public function get(AddFileRequest_get_Request $request)
    {
        return $this->addFileRequestService->get($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddFileRequest $addFileRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AddFileRequest $addFileRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddFileRequest $addFileRequest)
    {
        //
    }

    public function response(AddFileRequest_response_Request $request) {
        return $this->addFileRequestService->response($request);
    }
}
