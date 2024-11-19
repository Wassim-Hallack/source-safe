<?php

namespace App\Services;

use App\Http\Requests\AddFileRequest_get_Request;
use App\Models\AddFileRequest;
use App\Models\Group;
use App\Models\User;
use App\Repositories\AddFileRequestRepository;
use Illuminate\Http\Request;

class AddFileRequestService
{
    protected AddFileRequestRepository $addFileRequestRepository;

    public function __construct(AddFileRequestRepository $addFileRequestRepository)
    {
        $this->addFileRequestRepository = $addFileRequestRepository;
    }

    public function get(AddFileRequest_get_Request $request) {
        $add_file_requests = Group::find($request['group_id'])->add_file_requests;
        foreach ($add_file_requests as $add_file_request) {
            if(!$add_file_request['isFree']) {
                $add_file_request_to_user = $add_file_request->user;
                $user = User::find($add_file_request_to_user['user_id']);

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
}
