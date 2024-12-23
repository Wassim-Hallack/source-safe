<?php

namespace App\Services\Admin;

use App\Repositories\GroupRepository;

class GroupService
{
    public function get()
    {
        $groups = GroupRepository::get();

        return response()->json([
            'status' => true,
            'response' => $groups,
        ]);
    }
}
