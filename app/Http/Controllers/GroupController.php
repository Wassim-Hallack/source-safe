<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
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
        Group::create([
            'name' => $data['name'],
            'user_id' => $user['id']
        ]);

        return response()->json([
            'status' => true,
            'response' => "Group created successfully.",
        ], 200);
    }

    public function get()
    {
        $user = Auth::user();
        $groups = User::find($user['id'])->groups;

        return response()->json([
            'status' => true,
            'response' => $groups,
        ], 200);
    }
}
