<?php

namespace App\Services\Contractors;

use App\User;

interface NotificationsInterface
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
    public function sendSMS(User $user, $title, $message, $url, $send = true);

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
    public function sendEmail(User $user, $title, $message, $url, $type, $send = true);

}