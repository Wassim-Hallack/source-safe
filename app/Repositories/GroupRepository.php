<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository
{
    public function get()
    {
        return Group::all();
    }

    public function create($data)
    {
        return Group::create($data);
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
