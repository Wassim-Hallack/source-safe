<?php

namespace App\Services;

use App\Http\Requests\FileAddRequest;
use App\Http\Requests\FileDestroyRequest;
use App\Http\Requests\FileEditRequest;
use App\Http\Requests\FileGetRequest;
use App\Models\File;
use App\Models\Group;
use App\Models\UserFile;
use App\Repositories\AddFileRequestRepository;
use App\Repositories\AddFileRequestToUserRepository;
use App\Repositories\FileRepository;
use App\Repositories\UserFileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as LaravelFile;

class FileService
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function get(FileGetRequest $request)
    {
        $group_id = $request['group_id'];
        $files = Group::find($group_id)->files;

        return response()->json([
            'status' => true,
            'response' => $files,
        ], 200);
    }

    public function add(FileAddRequest $request)
    {
        $data = $request->all();
        $logged_in_user = Auth::user();

        $data['file_status'] = $data['file_status'][0];
        $data['file_status'] = (boolean)$data['file_status'];

        $file = $data['file'];
        $file_name = $file->getClientOriginalName();
        $file_status = $data['file_status'];
        $group_id = $data['group_id'];
        $user_id = $data['user_id'];

        $group = Group::find($group_id);
        if ($group['user_id'] === $logged_in_user['id']) {
            $file_path = 'Groups/' . $group['name'] . "/" . $file_name;
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $file->storeAs($file_path, "1." . $file_extension);

            $file_data['name'] = $file_name;
            $file_data['group_id'] = $group_id;

            if (!$file_status) {
                $file_data['isFree'] = false;
                $file_record = $this->fileRepository->create($file_data);

                $user_file_data['user_id'] = $user_id;
                $user_file_data['file_id'] = $file_record['id'];
                (new UserFileRepository())->create($user_file_data);
            } else {
                $file_data['isFree'] = true;
                $this->fileRepository->create($file_data);
            }
        } else {
            $add_file_request_data['group_id'] = $group_id;
            $add_file_request_data['name'] = $file_name;

            if ($file_status) {
                $add_file_request_data['isFree'] = false;
                $add_file_request_record = (new AddFileRequestRepository())->create($add_file_request_data);

                $add_file_request_to_user_data['add_file_request_id'] = $add_file_request_record['id'];
                $add_file_request_to_user_data['user_id'] = $user_id;
                (new AddFileRequestToUserRepository())->create($add_file_request_to_user_data);
            } else {
                $add_file_request_data['isFree'] = true;
                (new AddFileRequestRepository())->create($add_file_request_data);
            }
        }

        return response()->json([
            'status' => true,
            'response' => 'The file saved successfully.'
        ], 200);
    }

    public function edit(FileEditRequest $request)
    {
        $data = $request->all();
        $user = Auth::user();

        $group_id = $data['group_id'];
        $group = Group::find($group_id);
        $file = $data['file'];
        $file_name = $file->getClientOriginalName();

        $file_record = File::where('name', $file_name)
            ->where('group_id', $group_id)
            ->where('isFree', false)
            ->first();

        $user_file = UserFile::where('user_id', $user['id'])
            ->where('file_id', $file_record['id'])
            ->first();

        (new UserFileRepository())->delete($user_file);

        $file_record['isFree'] = true;
        $file_record->save();

        $path = storage_path('app\\Groups\\' . $group['name'] . '\\' . $file_name);
        $fileCount = count(LaravelFile::files($path));

        $path = 'Groups/' . $group['name'] . "/" . $file_name;
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $file->storeAs($path, $fileCount . "." . $file_extension);

        return response()->json([
            'status' => true,
            'response' => 'The file updated successfully.'
        ]);
    }

    public function destroy(FileDestroyRequest $request)
    {
        $file_id = $request['file_id'];
        $file = File::find($file_id);

        $this->fileRepository->delete($file);

        return response()->json([
            'status' => true,
            'response' => 'The file deleted successfully.'
        ], 200);
    }
}
