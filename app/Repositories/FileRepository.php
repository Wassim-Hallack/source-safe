<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Support\Facades\Cache;

class FileRepository
{
    static public function get()
    {
        return File::all();
    }

    static public function find($id)
    {
        return File::find($id);
    }

    static public function create($data)
    {
        Cache::forget(self::group_files_cache($data['group_id']));
        return File::create($data);
    }

    static public function update($record, $data)
    {
        Cache::forget(self::group_files_cache($record['group_id']));
        $record->update($data);
        return $record;
    }

    static public function delete($record)
    {
        Cache::forget(self::group_files_cache($record['group_id']));
        $record->delete();
    }

    static public function findByConditions(array $conditions)
    {
        $query = File::query();

        foreach ($conditions as $column => $condition) {
            if (is_array($condition) && count($condition) === 2) {
                [$operator, $value] = $condition;
                $query->where($column, $operator, $value);
            } else {
                $query->where($column, '=', $condition);
            }
        }

        return $query->first();
    }

    static public function existsByConditions(array $conditions)
    {
        $query = File::query();

        foreach ($conditions as $column => $condition) {
            if (is_array($condition) && count($condition) === 2) {
                [$operator, $value] = $condition;
                $query->where($column, $operator, $value);
            } else {
                $query->where($column, '=', $condition);
            }
        }

        return $query->exists();
    }

    static public function getFilesByIds(array $fileIds)
    {
        return File::query()
            ->whereIn('id', $fileIds)
            ->get();
    }

    static public function group_files_cache($group_id)
    {
        return 'group_files_cache.' . $group_id;
    }
}
