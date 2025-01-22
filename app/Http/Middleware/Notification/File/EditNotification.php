<?php

namespace App\Http\Middleware\Notification\File;

use App\Repositories\NotificationRepository;
use App\Services\Firebase\Notification;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EditNotification
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $title = 'File Check-out';
        $description = $request['user']['name'] . ' has checked out ' .
            $request['file']['name'] . ' file in ' .
            $request['group']['name'] . ' group by ' . $request['user']['name'] . '.';

        $users_group_in = $request['group']->users_group_in;
        foreach ($users_group_in as $user_group_in) {
            Notification::send($user_group_in['fcm_token'], $title, $description);

            $notification_record = [
                'user_id' => $user_group_in['id'],
                'title' => $title,
                'description' => $description
            ];
            NotificationRepository::create($notification_record);
        }

        return $response;
    }
}
