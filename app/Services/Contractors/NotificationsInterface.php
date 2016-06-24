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
     * @return mixed
     */
    public function sendSMS( $title, $message, $url);

    /**
     * Send Email functionality.
     *
     * @param $title
     * @param $message
     * @param $url
     * @param $type
     * @return mixed
     */
    public function sendEmail( $title, $message, $url, $type);

}