<?php

namespace App\Repositories;

use App\Models\FileOperation;

class FileOperationRepository
{
    static public function get()
    {
        return FileOperation::all();
    }

    static public function find($id)
    {
        return FileOperation::find($id);
    }

    static public function create($data)
    {
        return FileOperation::create($data);
    }

    static public function update($record, $data)
    {
        $record->update($data);
        return $record;
    }

    static public function delete($record)
    {
        $record->delete();
    }

    static public function findByConditions(array $conditions)
    {
        $query = FileOperation::query();

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
        $query = FileOperation::query();

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

    static public function getByConditionsWithRelations(array $conditions = [], array $relations = [])
    {
        $query = FileOperation::query();

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }
}
