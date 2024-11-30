<?php

namespace App\Services;

use App\Http\Requests\FileOperation_export_file_operations_Request;
use App\Http\Requests\FileOperation_get_file_operations_Request;
use App\Http\Requests\FileOperation_get_user_operations_Request;
use App\Models\FileOperation;
use App\Repositories\FileOperationRepository;
use App\Repositories\FileRepository;
use App\Services\Export\ExportContext;
use App\Services\Export\PdfExport;

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

    public function export_file_operations(FileOperation_export_file_operations_Request $request)
    {
        $data['file'] = FileRepository::find($request['file_id']);
        $data['group'] = $data['file']->group;
        unset($data['file']['group']);

        $conditions = [
            'file_id' => $request['file_id']
        ];
        $relations = [
            'user:id,name,email'
        ];
        $file_operations = FileOperationRepository::getByConditionsWithRelations($conditions, $relations);
        $data['file_operations'] = $file_operations;
//        return $data;

        $export_strategy = match ($request['export_type']) {
            'pdf' => new PdfExport(),
//            'excel' => new ExcelExport(),
//            default => throw new \InvalidArgumentException('Invalid export type')
        };

        $exportContext = new ExportContext($export_strategy);

        return $exportContext->export($data);
    }
}
