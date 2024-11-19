<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupInvitationResponseRequest;
use App\Models\GroupInvitation;
use App\Services\GroupInvitationService;
use App\Traits\LogExecutionTrait;
use Illuminate\Http\Request;

class GroupInvitationController extends Controller
{
    use LogExecutionTrait;
    protected $groupInvitationService;

    public function __construct(GroupInvitationService $groupInvitationService)
    {
        $this->groupInvitationService = $groupInvitationService;
    }

    public function invitation_response(GroupInvitationResponseRequest $request) {
        return $this->logExecution(function () use ($request) {
            return $this->groupInvitationService->invitation_response($request);
        }, __FUNCTION__, $request->all());
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
