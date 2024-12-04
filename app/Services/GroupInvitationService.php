<?php

namespace App\Services;

use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class GroupInvitationService
{
    public function create($request)
    {
        $user = UserRepository::find($request['user_id']);
        if ($user === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid user_id.'
            ], 400);
        }

        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        $request['logged_in_user'] = Auth::user();

        if ($group['user_id'] !== $request['logged_in_user']['id']) {
            return response()->json([
                'status' => false,
                'response' => 'The logged in user is not the admin of this group.'
            ], 400);
        }

        $conditions = [
            'user_id' => $request['user_id'],
            'group_id' => $request['group_id']
        ];
        $is_exists_previous_invitation = GroupInvitationRepository::existsByConditions($conditions);
        if ($is_exists_previous_invitation) {
            return response()->json([
                'status' => false,
                'response' => 'There is previous invitation for this user to this group.'
            ], 400);
        }

        $data['user_id'] = $request['user_id'];
        $data['group_id'] = $request['group_id'];
        GroupInvitationRepository::create($data);

        return response()->json([
            'status' => true,
            'message' => 'The invitation has send successfully.'
        ]);
    }

    public function invitation_response($request)
    {
        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        $group_id = $request['group_id'];
        $user = Auth::user();

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group_id
        ];
        $invitation = GroupInvitationRepository::findAllByConditions($conditions);

        if (!count($invitation)) {
            return response()->json([
                'status' => false,
                'response' => 'There is no invitation for this user to this group.'
            ], 400);
        }

        $invitation = $invitation->first();
        GroupInvitationRepository::delete($invitation);

        $response = $request['response'];
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
        ]);
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
        ]);
    }
}
