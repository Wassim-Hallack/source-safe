<?php

namespace App\Repositories;

use App\Models\UserFile;

class UserFileRepository
{
    public function get()
    {
        return UserFile::all();
    }

    public function create($data)
    {
        return UserFile::create($data);
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
