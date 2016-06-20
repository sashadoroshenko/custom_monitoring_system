<?php

namespace App\Services\Contractors;

use App\User;

interface NotificationsInterface
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
    public function sendSMS( $title, $message, $url, $send = true);

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
    public function sendEmail( $title, $message, $url, $type, $send = true);

}