<?php

namespace App\Repositories;

use App\Models\UserGroup;

class UserGroupRepository
{
    public function get()
    {
        return UserGroup::all();
    }

    public function create($data)
    {
        return UserGroup::create($data);
    }

    public function update($record, $data)
    {
        $record->update($data);
        return $record;
    }

    public function delete($record)
    {
        $record->delete();
    }
}
