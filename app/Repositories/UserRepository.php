<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function get()
    {
        return User::all();
    }

    public function create($data)
    {
        return User::create($data);
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
