<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserRepository
{
    protected static string $all_users_cache = 'all_users_cache';

    static public function get()
    {
        return Cache::remember(self::$all_users_cache, now()->addDay(), function () {
            return User::all();
        });
    }

    static public function find($id)
    {
        return User::find($id);
    }

    static public function create($data)
    {
        self::clearCache();
        return User::create($data);
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

    static public function getUserGroupsWithRelations($userId, array $relations = [])
    {
        $query = User::with($relations)->find($userId);

        return $query?->groups_user_in;
    }

    static public function findAllByConditions(array $conditions)
    {
        $query = User::query();

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

    static public function findByConditions(array $conditions)
    {
        $query = User::query();

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
        $query = User::query();

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

    static protected function clearCache()
    {
        Cache::forget(self::$all_users_cache);
    }
}
