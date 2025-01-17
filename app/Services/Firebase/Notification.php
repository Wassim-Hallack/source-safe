<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Throwable;

class Notification
{
    public static function send($fcm_token, $title, $description, $data)
    {
        $service_account_path = storage_path('app/json/firebase-service-account.json');

        try {
            $firebase = (new Factory)
                ->withServiceAccount($service_account_path)
                ->createMessaging();

            $message = CloudMessage::withTarget('token', $fcm_token)
                ->withNotification(['title' => $title, 'description' => $description])
                ->withData($data);

            $firebase->send($message);
        } catch (Throwable) {
            //
        }
    }
}
