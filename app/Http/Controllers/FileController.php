<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function get(Request $request)
    {
        $group_id = $request['group_id'];
        $files = Group::find($group_id)->files;

        return response()->json([
            'status' => true,
            'response' => $files,
        ], 200);
    }
}
