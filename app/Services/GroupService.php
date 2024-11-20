<?php

namespace App\Services;

use App\Http\Requests\Group_create_Request;
use App\Http\Requests\Group_users_out_group_Request;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function create(Group_create_Request $request)
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
        ], 200);
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
        ], 200);
    }

    public function users_out_group(Group_users_out_group_Request $request)
    {
        $group_id = $request['group_id'];
        $group = GroupRepository::find($group_id);

        if ($group === null) {
            return response()->json([
                'status' => true,
                'response' => [],
            ], 200);
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
        ], 200);
    }
}
