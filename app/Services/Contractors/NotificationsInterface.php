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
     * @return mixed
     */
    public function sendSMS(User $user, $title, $message, $url);

    /**
     * Send Email functionality.
     *
     * @param User $user
     * @param $title
     * @param $message
     * @param $url
     * @param $type
     * @return mixed
     */
    public function sendEmail(User $user, $title, $message, $url, $type);

}