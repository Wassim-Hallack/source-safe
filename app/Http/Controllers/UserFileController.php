<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use App\Services\UserFileService;
use Illuminate\Http\Request;

class UserFileController extends Controller
{
    protected UserFileService $userFileService;

    public function __construct(UserFileService $userFileService)
    {
        $this->userFileService = $userFileService;
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
    public function show(UserFile $userFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserFile $userFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserFile $userFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserFile $userFile)
    {
        //
    }
}
