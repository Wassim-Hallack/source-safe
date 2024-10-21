<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\User;
use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function add(Request $request)
    {
        $data = $request->all();
        if (($data['file_status'][0] !== "0" && $data['file_status'][0] !== "1")) {
            return response()->json([
                'status' => false,
                'response' => 'There is something wrong in file_status field.'
            ], 400);
        }

        $data['file_status'] = $data['file_status'][0];
        $data['file_status'] = (boolean)$data['file_status'];

        $validator = Validator::make($data, [
            'file' => ['required', 'mimes:txt,pdf', 'max:2048'],
            'file_status' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'response' => "There is something wrong in some fields.",
            ], 400);
        }

        $file = $data['file'];
        $file_name = $file->getClientOriginalName();
        $file_status = $data['file_status'];
        $group_id = $data['group_id'];
        $user_id = $data['user_id'];

        $user = User::find($user_id);
        if ($user === null) {
            return response()->json([
                'status' => false,
                'response' => "There is no user with this id.",
            ], 400);
        }

        $group = Group::find($group_id);
        if ($group) {
            $files_in_the_same_group = $group->files->pluck('name')->toArray();

            if (in_array($file_name, $files_in_the_same_group)) {
                return response()->json([
                    'status' => false,
                    'response' => 'There is file with the same name in this group.'
                ], 400);
            } else {
                $file->move(storage_path('app'), $file_name);
                if ($file_status) {
                    $file_record = File::create([
                        'name' => $file_name,
                        'isFree' => true
                    ]);

                    UserFile::create([
                        'user_id' => $user_id,
                        'file_id' => $file_record['id']
                    ]);
                } else {
                    $file_record = File::create([
                        'name' => $file_name,
                        'isFree' => false,
                    ]);
                }

                GroupFile::create([
                    'group_id' => $group_id,
                    'file_id' => $file_record['id']
                ]);

                return response()->json([
                    'status' => true,
                    'response' => 'The file saved successfully.'
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'response' => 'Group not found.'
        ], 400);
    }
}
