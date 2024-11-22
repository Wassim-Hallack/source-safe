<?php

namespace App\Services;

use App\Http\Requests\FileOperation_get_file_operations_Request;
use App\Repositories\FileOperationRepository;

class FileOperationService
{
    public function get_file_operations(FileOperation_get_file_operations_Request $request) {
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
        ]);
    }
}
