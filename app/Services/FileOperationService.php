<?php

namespace App\Services;

use App\Repositories\FileOperationRepository;
use App\Repositories\FileRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use App\Services\Export\CsvExport;
use App\Services\Export\ExportContext;
use App\Services\Export\PdfExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class FileOperationService
{
    public function get_file_operations($request): JsonResponse
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
            'group_id' => $group['id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);

        if(!$is_exists) {
            return response()->json([
                'status' => false,
                'response' => 'The logged-in user is not in the file\'s group.'
            ], 400);
        }

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

    public function get_user_operations($request): JsonResponse
    {
        $user = UserRepository::find($request['user_id']);
        if ($user === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid user_id.'
            ], 400);
        }

        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        if (!$group['is_owner']) {
            return response()->json([
                'status' => false,
                'response' => 'The logged-in user is not the admin of this group.'
            ], 400);
        }

        $conditions = [
            'user_id' => $request['user_id'],
            'group_id' => $request['group_id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if (!$is_exists) {
            return response()->json([
                'status' => false,
                'response' => 'This user does not belong to this group.'
            ], 400);
        }

        $user_operations = FileOperationRepository::getUserOperations(
            ['group_id' => $request['group_id']],
            ['file:id,name']
        );

        return response()->json([
            'status' => true,
            'response' => $user_operations,
        ]);
    }

    public function export_file_operations($request)
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
            'group_id' => $group['id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);

        if(!$is_exists) {
            return response()->json([
                'status' => false,
                'response' => 'The logged-in user is not in the file\'s group.'
            ], 400);
        }

        $conditions = [
            'file_id' => $request['file_id']
        ];
        $relations = [
            'user:id,name,email'
        ];
        $file_operations = FileOperationRepository::getByConditionsWithRelations($conditions, $relations);
        $export_strategy = match ($request['export_type']) {
            'pdf' => (function () use ($file, $group, $file_operations) {
                $data['file'] = $file;
                $data['group'] = $group;
                $data['file_operations'] = $file_operations;

                return new PdfExport('file_operations', 'file_operations', $data);
            })(),

            'csv' => (function () use ($file, $group, $file_operations) {
                $data[] = ['Group Name', $group['name']];
                $data[] = [];

                $data[] = ['File Name', $file['name']];
                $data[] = [];

                $data[] = ['Operation ID', 'Username', 'Operation Type', 'Created At'];

                foreach ($file_operations as $operation) {
                    $data[] = [
                        $operation['id'],
                        $operation['user']['name'],
                        $operation['operation'],
                        $operation['created_at'],
                    ];
                }

                return new CsvExport('file_operations', $data);
            })(),

            default => throw new InvalidArgumentException('Invalid export type')
        };

        $exportContext = new ExportContext($export_strategy);

        return $exportContext->export();
    }

    public function export_user_operations($request)
    {
        $user = UserRepository::find($request['user_id']);
        if ($user === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid user_id.'
            ], 400);
        }

        $group = GroupRepository::find($request['group_id']);
        if ($group === null) {
            return response()->json([
                'status' => false,
                'response' => 'Invalid group_id.'
            ], 400);
        }

        if (!$group['is_owner']) {
            return response()->json([
                'status' => false,
                'response' => 'The logged-in user is not the admin of this group.'
            ], 400);
        }

        $conditions = [
            'user_id' => $request['user_id'],
            'group_id' => $request['group_id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if (!$is_exists) {
            return response()->json([
                'status' => false,
                'response' => 'This user does not belong to this group.'
            ], 400);
        }

        $user_operations = FileOperationRepository::getUserOperations(
            ['group_id' => $request['group_id']],
            ['file:id,name']
        );

        $export_strategy = match ($request['export_type']) {
            'pdf' => (function () use ($group, $user, $user_operations) {
                $data['group'] = $group;
                $data['user'] = $user;
                $data['user_operations'] = $user_operations;

                return new PdfExport('user_operations', 'user_operations', $data);
            })(),

            'csv' => (function () use ($group, $user, $user_operations) {
                $data[] = ['Group Name', $group['name']];
                $data[] = [];

                $data[] = ['User Name', $user['name']];
                $data[] = ['User Email', $user['email']];
                $data[] = [];

                $data[] = ['Operation ID', 'File Name', 'Operation Type', 'Created At'];

                foreach ($user_operations as $operation) {
                    $data[] = [
                        $operation['id'],
                        $operation['file']['name'],
                        $operation['operation'],
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
