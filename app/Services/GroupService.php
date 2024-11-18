<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'max:250', 'unique:groups,name'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong with some fields.",
            ], 400);
        }

        $user = Auth::user();
        $group_data['name'] = $data['name'];
        $group_data['user_id'] = $user['id'];

        $group = $this->groupRepository->create($group_data);

        $user_group_data['user_id'] = $user['id'];
        $user_group_data['group_id'] = $group['id'];
        (new UserGroupRepository)->create($user_group_data);

        return response()->json([
            'status' => true,
            'response' => "Group created successfully.",
        ], 200);
    }

    public function get()
    {
        $user = Auth::user();
        $groups = User::with('groups_user_in.user')->find($user['id'])->groups_user_in;

        return response()->json([
            'status' => true,
            'response' => $groups,
        ], 200);
    }

    public function users_out_group(Request $request)
    {
        $group_id = $request['group_id'];
        $group = Group::find($group_id);

        if ($group === null) {
            return response()->json([
                'status' => true,
                'response' => [],
            ], 200);
        }

        $all_users = (new UserRepository)->get();
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
