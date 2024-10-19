<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function get()
    {
        $user = Auth::user();
        $groups = User::find($user['id'])->groups;

        return response()->json([
            'message' => 'success',
            'groups' => $groups,
        ], 200);
    }
}
