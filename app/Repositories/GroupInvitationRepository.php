<?php

namespace App\Repositories;

use App\Models\GroupInvitation;

class GroupInvitationRepository
{
    public function get()
    {
        return GroupInvitation::all();
    }

    public function create($data)
    {
        return GroupInvitation::create($data);
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
