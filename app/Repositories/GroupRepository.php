<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository
{
    static public function get()
    {
        return Group::all();
    }

    static public function find($id)
    {
        return Group::find($id);
    }

    static public function create($data)
    {
        return Group::create($data);
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
}
