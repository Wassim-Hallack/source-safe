<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository
{
    static public function get()
    {
        return File::all();
    }

    static public function find($id) {
        return File::find($id);
    }

    static public function create($data)
    {
        return File::create($data);
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

    static public function updateAttributes($record, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $record[$key] = $value;
        }
        $record->save();

        return $record;
    }
}
