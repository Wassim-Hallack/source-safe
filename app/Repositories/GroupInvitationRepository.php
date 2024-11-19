<?php

namespace App\Repositories;

use App\Models\GroupInvitation;

class GroupInvitationRepository
{
    static public function get()
    {
        return GroupInvitation::all();
    }

    static public function create($data)
    {
        return GroupInvitation::create($data);
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
        $query = GroupInvitation::query();

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

    static public function findByConditions(array $conditions)
    {
        $query = GroupInvitation::query();

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
        $query = GroupInvitation::query();

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

    static public function getByConditionsWithRelations(array $conditions = [], array $relations = [])
    {
        $query = GroupInvitation::query();

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }
}
