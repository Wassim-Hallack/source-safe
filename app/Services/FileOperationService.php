<?php

namespace App\Services;

use App\Http\Requests\FileOperation_get_file_operations_Request;
use App\Http\Requests\FileOperation_get_user_operations_Request;
use App\Models\FileOperation;
use App\Repositories\FileOperationRepository;

class FileOperationService
{
    public function get_file_operations(FileOperation_get_file_operations_Request $request)
    {
        $conditions = [
            'file_id' => $request['file_id']
        ];
        $relations = [
            'user:id,name,email'
        ];
        $file_operations = FileOperationRepository::getByConditionsWithRelations($conditions, $relations);

        return response()->json([
            'status' => true,
            'response' => $file_operations
        ], 200);
    }

    public function get_user_operations(FileOperation_get_user_operations_Request $request)
    {
        $user_operations = FileOperationRepository::getUserOperations(
            ['group_id' => $request['group_id']],
            ['file:id,name']
        );

        return response()->json([
            'status' => true,
            'response' => $user_operations,
        ], 200);
    }
}
