<?php

namespace App\Repositories;

use App\Models\AddFileRequestToUser;

class AddFileRequestToUserRepository
{
    static public function get()
    {
        return AddFileRequestToUser::all();
    }

    static public function create($data)
    {
        return AddFileRequestToUser::create($data);
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
