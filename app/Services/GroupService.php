<?php

namespace App\Services;

use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function create($request)
    {
        $user = Auth::user();
        $group_data['name'] = $request['name'];
        $group_data['user_id'] = $user['id'];

        $group = GroupRepository::create($group_data);

        $user_group_data['user_id'] = $user['id'];
        $user_group_data['group_id'] = $group['id'];
        UserGroupRepository::create($user_group_data);

        return response()->json([
            'status' => true,
            'response' => "Group created successfully.",
        ]);
    }

    public function get()
    {
        $user = Auth::user();

        $relations = [
            'groups_user_in:id,name,user_id',
            'groups_user_in.user:id,email,name',
        ];
        $groups = UserRepository::getUserGroupsWithRelations($user['id'], $relations);

        return response()->json([
            'status' => true,
            'response' => $groups,
        ]);
    }

    public function users_out_group($request)
    {
        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => true,
                'response' => [],
            ]);
        }

        $all_users = UserRepository::get();
        $users_ids_in_group = $group->users_group_in->pluck('id')->toArray();

        $filtered_users = [];
        foreach ($all_users as $user) {
            if (!in_array($user['id'], $users_ids_in_group)) {
                $filtered_users[] = $user;
            }
        }

        return response()->json([
            'status' => true,
            'response' => $filtered_users,
        ]);
    }

    public function users_in_group($request)
    {
        $request['group'] = GroupRepository::find($request['group_id']);
        if ($request['group'] === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        $users = $request['group']->users_group_in;

        return response()->json([
            'status' => true,
            'response' => $users
        ]);
    }
}
