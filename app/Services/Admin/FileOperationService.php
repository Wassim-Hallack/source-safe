<?php

namespace App\Services\Admin;

use App\Repositories\FileOperationRepository;
use App\Repositories\UserRepository;
use App\Services\Export\CsvExport;
use App\Services\Export\ExportContext;
use App\Services\Export\PdfExport;
use InvalidArgumentException;

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

    public function export_all_user_operations($request)
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
        $user_operations = FileOperationRepository::getByConditionsWithRelations($conditions, $relations);

        $export_strategy = match ($request['export_type']) {
            'pdf' => (function () use ($user, $user_operations) {
                $data['user'] = $user;
                $data['user_operations'] = $user_operations;

                return new PdfExport('all_user_operations', 'all_user_operations', $data);
            })(),

            'csv' => (function () use ($user, $user_operations) {
                $data[] = ['User Name', $user['name']];
                $data[] = ['User Email', $user['email']];
                $data[] = [];

                $data[] = ['Operation ID', 'Group Name', 'File Name', 'Operation Type', 'Current Version', 'Comparison Result', 'Created At'];

                foreach ($user_operations as $operation) {
                    $data[] = [
                        $operation['id'],
                        $operation['file']['group']['name'],
                        $operation['file']['name'],
                        $operation['operation'],
                        $operation['current_version'],
                        $operation['comparison_result'],
                        $operation['created_at'],
                    ];
                }

                return new CsvExport('user_operations', $data);
            })(),

            default => throw new InvalidArgumentException('Invalid export type')
        };

        $exportContext = new ExportContext($export_strategy);

        return $exportContext->export();
    }
}
