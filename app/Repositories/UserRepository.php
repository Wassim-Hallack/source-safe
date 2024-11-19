<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    static public function get()
    {
        return User::all();
    }

    static public function find($id)
    {
        return User::find($id);
    }

    static public function create($data)
    {
        return User::create($data);
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

    static public function getUserGroupsWithRelations($userId, array $relations = [])
    {
        $query = User::with($relations)->find($userId);

        return $query?->groups_user_in;
    }
}
