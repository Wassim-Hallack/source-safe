<?php

namespace App\Http\Controllers;

use App\Models\AddFileRequestToUser;
use App\Services\AddFileRequestToUserService;
use Illuminate\Http\Request;

class AddFileRequestToUserController extends Controller
{
    protected AddFileRequestToUserService $addFileRequestToUserService;

    public function __construct(AddFileRequestToUserService $addFileRequestToUserService)
    {
        $this->addFileRequestToUserService = $addFileRequestToUserService;
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
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddFileRequestToUser $addFileRequestToUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AddFileRequestToUser $addFileRequestToUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddFileRequestToUser $addFileRequestToUser)
    {
        //
    }
}
