<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function get()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        return response()->json([
            'status' => true,
            'response' => $notifications
        ]);
    }
}
