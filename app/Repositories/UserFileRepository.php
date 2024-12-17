<?php

namespace App\Repositories;

use App\Models\UserFile;

class UserFileRepository
{
    static public function get()
    {
        return UserFile::all();
    }

    static public function create($data)
    {
        return UserFile::create($data);
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
        $query = UserFile::query();

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

    static public function findAllByConditions(array $conditions)
    {
        $query = UserFile::query();

        foreach ($conditions as $column => $condition) {
            if (is_array($condition) && count($condition) === 2) {
                [$operator, $value] = $condition;
                $query->where($column, $operator, $value);
            } else {
                $query->where($column, '=', $condition);
            }
        }

        return $query->get();
    }
}
