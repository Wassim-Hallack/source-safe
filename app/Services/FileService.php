<?php

namespace App\Services;

use App\Repositories\AddFileRequestRepository;
use App\Repositories\AddFileRequestToUserRepository;
use App\Repositories\FileOperationRepository;
use App\Repositories\FileRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserFileRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File as LaravelFile;
use Illuminate\Support\Facades\Storage;

class FileService
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
        $group_id = $request->input('group_id');

        if ($group_id === null) {
            return response()->json([
                'status' => false,
                'response' => 'There is no group with this id.'
            ], 400);
        }

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group_id,
        ];
        $is_exists_user_group = UserGroupRepository::existsByConditions($conditions);

        if (!$is_exists_user_group && (isset($request['isAdmin']) && !$request['isAdmin'])) {
            return response()->json([
                'status' => false,
                'response' => 'The logged in user does not belong to this group.'
            ], 400);
        }

        $group_id = $request['group_id'];

        $files = Cache::remember(FileRepository::group_files_cache($group_id), now()->addDay(), function () use ($group_id) {
            return GroupRepository::find($group_id)->files()->get();
        });

        return response()->json([
            'status' => true,
            'response' => $files,
        ]);
    }

    public function add($request)
    {
        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        $group_id = $request['group_id'];
        $file = $request['file'];
        $file_name = $file->getClientOriginalName();
        $isFree = $request['isFree'];
        $user_id = $request['user_id'];

        $files_in_the_same_group = $group->files->pluck('name')->toArray();
        if (in_array($file_name, $files_in_the_same_group)) {
            return response()->json([
                'status' => false,
                'response' => 'There is file with the same name in this group.'
            ], 400);
        }

        $add_files_requests_in_the_same_group = $group->add_file_requests->pluck('name')->toArray();
        if (in_array($file_name, $add_files_requests_in_the_same_group)) {
            return response()->json([
                'status' => false,
                'response' => 'There is add file request with the same name in this group.'
            ], 400);
        }

        if (!$isFree) {
            $user = UserRepository::find($user_id);
            if ($user === null) {
                return response()->json([
                    'status' => false,
                    'response' => 'There is no user with this id.'
                ], 400);
            } else {
                $conditions = [
                    'user_id' => $user_id,
                    'group_id' => $group_id
                ];
                $is_exists = UserGroupRepository::existsByConditions($conditions);

                if (!$is_exists) {
                    return response()->json([
                        'status' => false,
                        'response' => 'This user does not belong to this group.'
                    ], 400);
                }

                $user = Auth::user();

                $conditions = [
                    'user_id' => $user['id'],
                    'group_id' => $group_id
                ];
                $is_exists = UserGroupRepository::existsByConditions($conditions);

                if (!$is_exists) {
                    return response()->json([
                        'status' => false,
                        'response' => 'The logged-in user does not belong to this group.'
                    ], 400);
                }
            }
        }

        $logged_in_user = Auth::user();
        $group = GroupRepository::find($group_id);

        if ($group['user_id'] === $logged_in_user['id']) {
            $file_path = 'Groups/' . $group['name'] . "/" . $file_name;
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $file->storeAs($file_path, "1." . $file_extension);

            $file_data['name'] = $file_name;
            $file_data['group_id'] = $group_id;

            if (!$isFree) {
                $file_data['isFree'] = false;
                $file_record = FileRepository::create($file_data);

                $user_file_data['user_id'] = $user_id;
                $user_file_data['file_id'] = $file_record['id'];
                UserFileRepository::create($user_file_data);

                $user_file_data['operation'] = 'check-in';
                FileOperationRepository::create($user_file_data);
            } else {
                $file_data['isFree'] = true;
                FileRepository::create($file_data);
            }
        } else {
            $file_path = 'Add File Requests/' . $group['name'];
            $file->storeAs($file_path, $file_name);

            $add_file_request_data['group_id'] = $group_id;
            $add_file_request_data['name'] = $file_name;

            if (!$isFree) {
                $add_file_request_data['isFree'] = false;
                $add_file_request_record = AddFileRequestRepository::create($add_file_request_data);

                $add_file_request_to_user_data['add_file_request_id'] = $add_file_request_record['id'];
                $add_file_request_to_user_data['user_id'] = $user_id;
                AddFileRequestToUserRepository::create($add_file_request_to_user_data);
            } else {
                $add_file_request_data['isFree'] = true;
                AddFileRequestRepository::create($add_file_request_data);
            }
        }

        return response()->json([
            'status' => true,
            'response' => 'The file saved successfully.'
        ]);
    }

    public function edit($request)
    {
        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        $data = $request->all();

        $user = Auth::user();
        $group_id = $data['group_id'];
        $file = $data['file'];
        $file_name = $file->getClientOriginalName();

        $conditions = [
            'group_id' => $group_id,
            'isFree' => false
        ];
        $file_record = FileRepository::findByConditions($conditions);
        if ($file_record !== null) {
            $conditions = [
                'user_id' => $user['id'],
                'file_id' => $file_record['id']
            ];
            $user_file = UserFileRepository::findByConditions($conditions);

            if ($user_file === null) {
                return response()->json([
                    'status' => false,
                    'response' => 'The logged in user does not have this file.'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'response' => 'There is no file with these attributes.'
            ], 400);
        }

        $conditions = [
            'name' => $file_name,
            'group_id' => $group_id,  // 'group_id' => ['>=', $group_id]
            'isFree' => false,
        ];
        $file_record = FileRepository::findByConditions($conditions);

        $conditions = [
            'user_id' => $user['id'],
            'file_id' => $file_record['id']
        ];
        $user_file = UserFileRepository::findByConditions($conditions);
        UserFileRepository::delete($user_file);

        $values = [
            'isFree' => true,
        ];
        FileRepository::update($file_record, $values);

        $data = [
            'user_id' => $user['id'],
            'file_id' => $file_record['id'],
            'operation' => 'check-out'
        ];
        FileOperationRepository::create($data);

        $path = storage_path('app/Groups/' . $group['name'] . '/' . $file_name);
        $fileCount = count(LaravelFile::files($path));
        $current_version = $fileCount + 1;

        $path = 'Groups/' . $group['name'] . "/" . $file_name;
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // Compare between the previous version and this version by Levenshtein Distance
        $old_file_path = $path . '/' . $fileCount . '.' . $file_extension;
        $old_file_content = Storage::get($old_file_path);
        $new_file_content = $file->get();
        $levenshtein_distance = levenshtein($old_file_content, $new_file_content);

        // Get the number of lines and characters in the old file
        $absolute_old_file_path = Storage::path($old_file_path);
        $old_file_line_count = 0;
        $old_file_character_count = 0;
        $handle = fopen($absolute_old_file_path, 'r');
        while (($line = fgets($handle)) !== false) {
            $old_file_line_count++;
            $old_file_character_count += strlen($line);
        }
        fclose($handle);

        // Get the number of lines and characters in the new file
        $new_file_line_count = 0;
        $new_file_character_count = 0;
        $handle = fopen($file->getPathname(), 'r');
        while (($line = fgets($handle)) !== false) {
            $new_file_line_count++;
            $new_file_character_count += strlen($line);
        }
        fclose($handle);

        // Format all comparison in one variable
        $comparison_result = "Levenshtein Distance = " . $levenshtein_distance .
            "\nNumber of lines: " .
            "Previous version = " . $old_file_line_count . ", New version = " . $new_file_line_count .
            "\nNumber of characters: " .
            "Previous version = " . $old_file_character_count . ", New version = " . $new_file_character_count;


        // Save file operation
        $data = [
            'user_id' => $user['id'],
            'file_id' => $file_record['id'],
            'operation' => 'check-out',
            'current_version' => $current_version,
            'comparison_result' => $comparison_result
        ];
        FileOperationRepository::create($data);

        $file->storeAs($path, $current_version . "." . $file_extension);

        // Handle request for middlewares
        $request['user'] = $user;
        $request['group'] = $group;
        $request['file'] =  $file_record;

        return response()->json([
            'status' => true,
            'response' => 'The file updated successfully.'
        ]);
    }

    public
    function destroy($request)
    {
        $file = FileRepository::find($request['file_id']);
        if ($file === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid file_id.'
            ], 400);
        }

        $user = Auth::user();
        $file_id = $request['file_id'];
        $group = FileRepository::find($file_id)->group;

        if ($group['user_id'] !== $user['id']) {
            return response()->json([
                'status' => false,
                'response' => 'The logged-in user is not the admin of this group.'
            ], 400);
        }

        $file = FileRepository::find($file_id);
        $directory_path = 'Groups/' . $group['name'] . '/' . $file['name'];

        if (Storage::exists($directory_path)) {
            Storage::deleteDirectory($directory_path);
        }
        FileRepository::delete($file);

        return response()->json([
            'status' => true,
            'response' => 'The file deleted successfully.'
        ]);
    }

    public
    function check_in($request)
    {
        foreach ($request['ids'] as $id) {
            $file = FileRepository::find($id);
            if ($file === null) {
                return response()->json([
                    'status' => false,
                    'response' => 'Invalid file_id.'
                ], 400);
            }
        }

        $request['files'] = FileRepository::getFilesByIds($request['ids']);

        $groupIds = $request['files']->pluck('group_id')->unique();
        if ($groupIds->count() > 1) {
            return response()->json([
                'status' => false,
                'response' => 'The provided file IDs belong to different groups.'
            ], 400);
        }

        $isAllFree = $request['files']->every(fn($file) => $file->isFree);
        if (!$isAllFree) {
            return response()->json([
                'status' => false,
                'response' => 'Some files are not free.'
            ], 400);
        }

        $user = Auth::user();

        foreach ($request['files'] as $file) {
            $data = [
                'user_id' => $user['id'],
                'file_id' => $file['id']
            ];
            UserFileRepository::create($data);

            $data['operation'] = 'check-in';
            FileOperationRepository::create($data);

            $values = ['isFree' => 0];
            FileRepository::update($file, $values);
        }

        // Handle request for middlewares
        $request['user'] = $user;
        $request['group'] = GroupRepository::find($groupIds);

        return response()->json([
            'status' => true,
            'response' => "Files checked in successfully."
        ]);
    }

    public
    function download($request)
    {
        $request['file'] = FileRepository::find($request['file_id']);
        if ($request['file'] === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid file_id.'
            ], 400);
        }

        $user = Auth::user();
        $request['group'] = $request['file']->group;

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $request['group']['id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if (!$is_exists && (isset($request['isAdmin']) && !$request['isAdmin'])) {
            return response()->json([
                'status' => false,
                'response' => 'The logged-in user does not belong to this group.'
            ], 400);
        }

        $directory_path = storage_path('app/Groups/' . $request['group']['name'] . '/' . $request['file']['name']);
        $fileCount = count(LaravelFile::files($directory_path));
        $file_extension = pathinfo($directory_path, PATHINFO_EXTENSION);

        $file_path = 'Groups/' . $request['group']['name'] . '/' . $request['file']['name'] . '/' . $fileCount . '.' . $file_extension;
        if (Storage::exists($file_path)) {
            $mimeType = Storage::mimeType($file_path) ?? 'application/octet-stream';
            $sanitizedFileName = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $request['file']['name']);

            return response()->download(storage_path('app/' . $file_path), $sanitizedFileName, ['Content-Type' => $mimeType]);
        } else {
            return response()->json([
                'status' => false,
                'response' => 'The file does not exist.'
            ], 400);
        }
    }

    public
    function get_file_versions($request)
    {
        $file = FileRepository::find($request['file_id']);
        if ($file === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid file_id.'
            ], 400);
        }

        $user = Auth::user();
        $group = $file->group;

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group['id'],
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if (!$is_exists && (isset($request['isAdmin']) && !$request['isAdmin'])) {
            return response()->json([
                'status' => false,
                'response' => 'This user do not have access to this file.'
            ], 400);
        }


        $directory_path = storage_path('app/Groups/' . $group['name'] . '/' . $file['name']);
        $fileCount = count(LaravelFile::files($directory_path));

        return response()->json([
            'status' => true,
            'response' => $fileCount
        ]);
    }

    public
    function download_version($request)
    {
        $file = FileRepository::find($request['file_id']);
        if ($file === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid file_id.'
            ], 400);
        }

        $user = Auth::user();
        $group = $file->group;

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group['id'],
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if (!$is_exists && (isset($request['isAdmin']) && !$request['isAdmin'])) {
            return response()->json([
                'status' => false,
                'response' => 'This user do not have access to this file.'
            ], 400);
        }

        $directory_path = storage_path('app/Groups/' . $group['name'] . '/' . $file['name']);
        $file_extension = pathinfo($directory_path, PATHINFO_EXTENSION);

        $file_path = 'Groups/' . $group['name'] . '/' . $file['name'] . '/' . $request['version_number'] . '.' . $file_extension;
        if (Storage::exists($file_path)) {
            $mimeType = Storage::mimeType($file_path) ?? 'application/octet-stream';
            $sanitizedFileName = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $file['name']);

            return response()->download(storage_path('app/' . $file_path), $sanitizedFileName, ['Content-Type' => $mimeType]);
        } else {
            return response()->json([
                'status' => false,
                'response' => 'The file does not exist.'
            ], 400);
        }
    }
}
