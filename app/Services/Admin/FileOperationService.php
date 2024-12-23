<?php

namespace App\Services\Admin;

use App\Repositories\FileOperationRepository;
use App\Repositories\UserRepository;

class FileOperationService
{
    public function get_all_user_operations($request)
    {
        $user = UserRepository::find($request['user_id']);
        if ($user === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid user_id.'
            ], 400);
        }

        $conditions = ['user_id' => $request['user_id']];
        $relations = ['file:id,name,group_id', 'file.group:id,name'];
        $user_operations = FileOperationRepository::getByConditionsWithRelationsAndPagination($conditions, $relations);

        return response()->json([
            'status' => true,
            'response' => $user_operations,
        ]);
    }
}
