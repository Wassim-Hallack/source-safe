<?php

namespace App\Services;

use App\Http\Requests\GroupInvitationResponseRequest;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\UserGroup;
use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupInvitationService
{
    public function create(Request $request)
    {
        $logged_in_user = Auth::user();
        $group_id = $request['group_id'];
        $user_id = $request['user_id'];

        $group = GroupRepository::find($group_id);
        if ($group['user_id'] !== $logged_in_user['id']) {
            return response()->json([
                'status' => false,
                'message' => 'The logged in user is not the admin of this group.'
            ], 400);
        }

        $conditions = [
            'user_id' => $user_id,
            'group_id' => $group_id
        ];
        $is_exists_previous_invitation = GroupInvitationRepository::existsByConditions($conditions);
        if ($is_exists_previous_invitation) {
            return response()->json([
                'status' => false,
                'message' => 'There is previous invitation for this user to this group.'
            ], 400);
        }

        $data['user_id'] = $user_id;
        $data['group_id'] = $group_id;
        GroupInvitationRepository::create($data);

        return response()->json([
            'status' => true,
            'message' => 'The invitation has send successfully.'
        ], 200);
    }

    public function invitation_response(GroupInvitationResponseRequest $request)
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
