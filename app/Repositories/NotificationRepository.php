<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    static public function get()
    {
        return Notification::all();
    }

    static public function find($id)
    {
        return Notification::find($id);
    }

    static public function create($data)
    {
        return Notification::create($data);
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
        $query = Notification::query();

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
