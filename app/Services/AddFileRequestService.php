<?php

namespace App\Services;

use App\Repositories\AddFileRequestRepository;
use App\Repositories\FileOperationRepository;
use App\Repositories\FileRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserFileRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AddFileRequestService
{
    public function get($request)
    {
        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        $user = Auth::user();

        if ($user['id'] !== $group['user_id']) {
            return response()->json([
                'status' => false,
                'response' => 'The logged in user is not the admin of the group.'
            ], 400);
        }

        $add_file_requests = GroupRepository::find($request['group_id'])->add_file_requests;
        foreach ($add_file_requests as $add_file_request) {
            if (!$add_file_request['isFree']) {
                $add_file_request_to_user = $add_file_request->user;
                $user = (new UserRepository())->find($add_file_request_to_user['user_id']);

                $add_file_request['to_user_name'] = $user['name'];
                $add_file_request['to_user_image'] = $user['image'];
                unset($add_file_request['user']);
            }
        }

        return response()->json([
            'status' => true,
            'response' => $add_file_requests
        ]);
    }

    public function response($request)
    {
        $add_file_request = AddFileRequestRepository::find($request['add_file_request_id']);
        if ($add_file_request === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid add_file_request_id.'
            ], 400);
        }

        $group = $add_file_request->group;

        if (!$group['is_owner']) {
            return response()->json([
                'status' => false,
                'response' => 'The logged in user is not the admin of this group.'
            ], 400);
        }

        $fileName = $add_file_request['name'];
        $group_id = $group['id'];

        $conditions = [
            'name' => $fileName,
            'group_id' => $group_id
        ];
        if (FileRepository::existsByConditions($conditions)) {
            return response()->json([
                'status' => false,
                'response' => 'There is a file with the same name in this group.'
            ], 400);
        }

        $old_path_file = 'Add File Requests/' . $group['name'] . "/" . $add_file_request['name'];
        if ($request['response']) {
            $extension = pathinfo($old_path_file, PATHINFO_EXTENSION);
            $new_path_file = 'Groups/' . $group['name'] . "/" . $add_file_request['name'] . "/1." . $extension;

            Storage::move($old_path_file, $new_path_file);

            $data = [
                'name' => $add_file_request['name'],
                'isFree' => $add_file_request['isFree'],
                'group_id' => $add_file_request['group_id']
            ];
            $file = FileRepository::create($data);

            if (!$file['isFree']) {
                $to_user = $add_file_request->user;

                $data_2 = [
                    'user_id' => $to_user['id'],
                    'file_id' => $file['id']
                ];
                UserFileRepository::create($data_2);

                $data_2['operation'] = 'check-in';
                FileOperationRepository::create($data_2);
            }
        } else {
            Storage::delete($old_path_file);
        }

        AddFileRequestRepository::delete($add_file_request);

        return response()->json([
            'status' => true,
            'response' => 'The file ' . ($request['response'] ? 'accepted' : 'rejected') . ' successfully.'
        ]);
    }
}
