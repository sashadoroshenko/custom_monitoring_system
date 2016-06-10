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
     * @param $title
     * @param $message
     * @return mixed
     */
    public function sendSMS($number, $title, $message)
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

        auth()->user()->notifications()->create([
            'status' => 1,
            'type' => 'phone',
            'contact_details' => auth()->user()->phone ? auth()->user()->phone : env('TWILIO_NUMBER_TO'),
            'title' => $title,
            'content' => $message
        ]);

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

        $content = '<strong>' . $itemID . '</strong> change ' . $type . '. Old ' . $type . ' ' . $oldValue . ' new ' . $type . ' ' . $newValue . '.';

        Mail::send('auth.emails.notification', [
            'title' => $title,
            'url' => $url,
            'content' => $content,
        ], function ($m) use ($title) {
            $m->to(auth()->user()->email)->subject($title);
        });

        auth()->user()->notifications()->create([
            'status' => 1,
            'type' => $type,
            'contact_details' => auth()->user()->email,
            'title' => $title,
            'content' => $content
        ]);

        auth()->user()->notifications()->create([
            'status' => 1,
            'type' => 'email',
            'contact_details' => auth()->user()->email,
            'title' => $title,
            'content' => $content
        ]);
    }
}