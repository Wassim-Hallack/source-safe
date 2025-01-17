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
        $description = $request['name'] . ' has checked out ' .
            $request['file']['name'] . ' file in ' .
            $request['group']['name'] . ' group by ' . $request['user']['name'] . '.';

        $users = $request['group']->users_group_in;
        foreach ($users as $user) {
            Notification::send($user['fcm_token'], $title, $description);

            $notification_record = [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => $description
            ];
            NotificationRepository::create($notification_record);
        }

        return $response;
    }
}
