<?php

namespace App\Repositories;

use App\Models\AddFileRequestToUser;

class AddFileRequestToUserRepository
{
    public function get()
    {
        return AddFileRequestToUser::all();
    }

    public function create($data)
    {
        return AddFileRequestToUser::create($data);
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
