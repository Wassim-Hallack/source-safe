<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Support\Facades\Cache;

class GroupRepository
{
    protected static string $all_groups_cache = 'all_groups_cache';

    static public function get()
    {
        return Cache::remember(self::$all_groups_cache, now()->addDay(), function () {
            return Group::all();
        });
    }

    static public function find($id)
    {
        return Group::find($id);
    }

    static public function create($data)
    {
        self::clearCache();
        return Group::create($data);
    }

    static public function update($record, $data)
    {
        self::clearCache();
        $record->update($data);
        return $record;
    }

    static public function delete($record)
    {
        self::clearCache();
        $record->delete();
    }

    static protected function clearCache()
    {
        Cache::forget(self::$all_groups_cache);
    }

    static public function existsByConditions(array $conditions)
    {
        $query = Group::query();

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
