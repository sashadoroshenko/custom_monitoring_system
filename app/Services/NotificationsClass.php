<?php

namespace App\Services;

use Services_Twilio_RestException;
use Illuminate\Support\Facades\Mail;
use App\Services\Contractors\NotificationsInterfase;

class NotificationsClass implements NotificationsInterfase
{

    /**
     * Send SMS functionality
     * 
     * @param $number
     * @param $message
     * @return mixed
     */
    public function sendSMS($number, $message)
    {
        $twilio = new \Aloha\Twilio\Twilio(
            config('twilio.twilio.connections.twilio.sid'),
            config('twilio.twilio.connections.twilio.token'),
            config('twilio.twilio.connections.twilio.from')
        );

        try {
            $message = $twilio->message($number, $message);
        } catch (Services_Twilio_RestException $e) {
            return $e->getMessage();
        };

        return $message;
    }

    /**
     * Send Email functionality
     * 
     * @param $itemID
     * @param null $newValue
     * @param null $oldValue
     * @param $title
     * @param $url
     * @param $type
     * @return mixed
     */
    public function sendEmail($itemID, $newValue = null, $oldValue = null, $title = null, $url = null, $type)
    {
        if (!isset($type) || empty($type)) {
            return;
        }

        Mail::send('auth.emails.notification', [
            'itemID' => $itemID,
            'newValue' => $newValue,
            'oldValue' => $oldValue,
            'title' => $title,
            'url' => $url,
            'type' => $type
        ], function ($m) {
            $m->to(auth()->user()->email)->subject('Hello!');
        });
    }
}