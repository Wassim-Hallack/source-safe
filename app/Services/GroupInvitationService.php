<?php

namespace App\Services;

use App\Http\Requests\GroupInvitation_create_Request;
use App\Http\Requests\GroupInvitation_response_Request;
use App\Models\GroupInvitation;
use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupInvitationService
{
    public function create($request)
    {
        $data['user_id'] = $request['user_id'];
        $data['group_id'] = $request['group_id'];
        GroupInvitationRepository::create($data);

        return response()->json([
            'status' => true,
            'message' => 'The invitation has send successfully.'
        ], 200);
    }

    public function invitation_response($request)
    {
        $group_id = $request['group_id'];
        $response = $request['response'];
        $user = Auth::user();

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group_id
        ];
        $invitation = GroupInvitationRepository::findByConditions($conditions);

        GroupInvitationRepository::delete($invitation);

        if ($response) {
            $is_exists = UserGroupRepository::existsByConditions($conditions);
            if (!$is_exists) {
                $data['user_id'] = $invitation['user_id'];
                $data['group_id'] = $invitation['group_id'];
                UserGroupRepository::create($data);
            }

            $response = 'You have joined to this group successfully.';
        } else {
            $response = 'You have rejected this invitation successfully.';
        }

        return response()->json([
            'status' => true,
            'response' => $response
        ], 200);
    }

    public function myInvitation()
    {
        $user = Auth::user();

        $conditions = ['user_id' => $user['id']];
        $relations = ['group:id,name'];
        $invitations = GroupInvitationRepository::getByConditionsWithRelations($conditions, $relations);

        return response()->json([
            'status' => true,
            'response' => $invitations
        ], 200);
    }
}
