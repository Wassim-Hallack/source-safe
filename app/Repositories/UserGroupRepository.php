<?php

namespace App\Repositories;

use App\Models\UserGroup;

class UserGroupRepository
{
    static public function get()
    {
        return UserGroup::all();
    }

    static public function create($data)
    {
        return UserGroup::create($data);
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

    static public function existsByConditions(array $conditions)
    {
        $query = UserGroup::query();

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
}
