<?php

namespace App\Http\Controllers;

use App\Models\GroupInvitation;
use App\Services\GroupInvitationService;
use Illuminate\Http\Request;

class GroupInvitationController extends Controller
{
    protected $groupInvitationService;

    public function __construct(GroupInvitationService $groupInvitationService)
    {
        $this->groupInvitationService = $groupInvitationService;
    }

    public function accept(Request $request) {
        return $this->groupInvitationService->accept($request);
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
    public function create(Request $request)
    {
        return $this->groupInvitationService->create($request);
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
    public function show(GroupInvitation $groupInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupInvitation $groupInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupInvitation $groupInvitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupInvitation $groupInvitation)
    {
        //
    }
}
