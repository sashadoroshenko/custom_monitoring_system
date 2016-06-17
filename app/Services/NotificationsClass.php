<?php

namespace App\Services;

use App\User;
use Services_Twilio_RestException;
use Illuminate\Support\Facades\Mail;
use App\Services\Contractors\NotificationsInterface;
use Swift_TransportException;

class NotificationsClass implements NotificationsInterface
{

    /**
     * Send SMS functionality.
     *
     * @param User $user
     * @param $title
     * @param $message
     * @param $url
     * @param $send
     * @return mixed
     */
    public function sendSMS(User $user, $title, $message, $url, $send = true)
    {
        $number = $user->phone ? $user->phone : env('TWILIO_NUMBER_TO');

        if($send) {
            $twilio = new \Aloha\Twilio\Twilio(
                config('twilio.twilio.connections.twilio.sid'),
                config('twilio.twilio.connections.twilio.token'),
                config('twilio.twilio.connections.twilio.from')
            );

            try {
                $twilio_message = $twilio->message($number, $message);
            } catch (Services_Twilio_RestException $e) {
                return $this->sendSMS($user, $title, $message, $url, $send);
            };
        }

        $user->notifications()->create([
            'status' => 1,
            'type' => 'phone',
            'contact_details' => $number,
            'title' => $title,
            'content' => $message
        ]);

        return $twilio_message;
    }

    /**
     * Send Email functionality.
     *
     * @param User $user
     * @param $title
     * @param $message
     * @param $url
     * @param $type
     * @param $send
     * @return mixed
     */
    public function sendEmail(User $user, $title, $message, $url, $type, $send = true)
    {
        if($send) {
            try {
                Mail::send('auth.emails.notification', [
                    'title' => $title,
                    'url' => $url,
                    'content' => $message,
                ], function ($m) use ($user, $title) {
                    $m->to($user->email)->subject($title);
                });
            } catch (Swift_TransportException $e) {
                $this->sendEmail($user, $title, $message, $url, $type);
            };
        }

        $user->notifications()->create([
            'status' => 1,
            'type' => $type,
            'contact_details' => $user->email,
            'title' => $title,
            'content' => $message
        ]);

        $user->notifications()->create([
            'status' => 1,
            'type' => 'email',
            'contact_details' => $user->email,
            'title' => $title,
            'content' => $message
        ]);
    }
}