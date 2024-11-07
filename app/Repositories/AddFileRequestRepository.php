<?php

namespace App\Repositories;

use App\Models\AddFileRequest;

class AddFileRequestRepository
{
    public function get()
    {
        return AddFileRequest::all();
    }

    public function create($data)
    {
        return AddFileRequest::create($data);
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
