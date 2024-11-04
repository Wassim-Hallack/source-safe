<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository
{
    public function get()
    {
        return File::all();
    }

    public function create($data)
    {
        return File::create($data);
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
