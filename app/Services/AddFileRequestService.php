<?php

namespace App\Services;

use App\Http\Requests\AddFileRequest_get_Request;
use App\Http\Requests\AddFileRequest_response_Request;
use App\Repositories\AddFileRequestRepository;
use App\Repositories\FileOperationRepository;
use App\Repositories\FileRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserFileRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;

class AddFileRequestService
{
    public function get(AddFileRequest_get_Request $request)
    {
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
        ], 200);
    }

    public function response(AddFileRequest_response_Request $request)
    {
        $old_path_file = 'Add File Requests/' . $request['group']['name'] . "/" . $request['add_file_request']['name'];
        if ($request['response']) {
            $extension = pathinfo($old_path_file, PATHINFO_EXTENSION);
            $new_path_file = 'Groups/' . $request['group']['name'] . "/" . $request['add_file_request']['name'] . "/1." . $extension;

            Storage::move($old_path_file, $new_path_file);

            $data = [
                'name' => $request['add_file_request']['name'],
                'isFree' => $request['add_file_request']['isFree'],
                'group_id' => $request['add_file_request']['group_id']
            ];
            $file = FileRepository::create($data);

            if (!$file['isFree']) {
                $to_user = $request['add_file_request']->user;

                $data_2 = [
                    'user_id' => $to_user['id'],
                    'file_id' => $file['id']
                ];
                UserFileRepository::create($data_2);

                $data_2['operation'] = 'check-in';
                FileOperationRepository::create($data_2);
            }
        }
        else {
            Storage::delete($old_path_file);
        }

        AddFileRequestRepository::delete($request['add_file_request']);

        return response()->json([
            'status' => true,
            'response' => 'The file ' . ($request['response'] ? 'accepted' : 'rejected') . ' successfully.'
        ]);
    }
}
