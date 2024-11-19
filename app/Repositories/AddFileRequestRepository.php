<?php

namespace App\Repositories;

use App\Models\AddFileRequest;

class AddFileRequestRepository
{
    static public function get()
    {
        return AddFileRequest::all();
    }

    static public function create($data)
    {
        return AddFileRequest::create($data);
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
