<?php

namespace App\Services;

use App\User;
use Services_Twilio_RestException;
use Illuminate\Support\Facades\Mail;
use App\Services\Contractors\NotificationsInterface;


class NotificationsClass implements NotificationsInterface
{

    /**
     * Send SMS functionality.
     *
     * @param $title
     * @param $message
     * @param $url
     * @param $send
     * @return mixed
     */
    public function sendSMS($title, $message, $url, $send = true)
    {
        $users = User::all();
        foreach ($users as $user) {

            $number = $user->phone ? $user->phone : env('TWILIO_NUMBER_TO');

            if ($send) {
                $twilio = new \Aloha\Twilio\Twilio(
                    config('twilio.twilio.connections.twilio.sid'),
                    config('twilio.twilio.connections.twilio.token'),
                    config('twilio.twilio.connections.twilio.from')
                );

                $this->send($twilio, $user, $number, $message);
            }

            $user->notifications()->create([
                'status' => 1,
                'type' => 'phone',
                'contact_details' => $number,
                'title' => $title,
                'content' => $message
            ]);
        }
    }

    private function send($twilio, $user, $number, $message)
    {
        try {
            $twilio->message($number, $message);
        } catch (Services_Twilio_RestException $e) {
            return $this->send($twilio, $user, $number, $message);
        };
    }

    /**
     * Send Email functionality.
     *
     * @param $title
     * @param $message
     * @param $url
     * @param $type
     * @param $send
     * @return mixed
     */
    public function sendEmail($title, $message, $url, $type, $send = true)
    {
        if ($send) {
            $emails = User::lists('email')->toArray();
            Mail::send('auth.emails.notification', [
                'title' => $title,
                'url' => $url,
                'content' => $message,
            ], function ($m) use ($emails, $title) {
                $m->to($emails)->subject($title);
            });
        }
    }
}