<?php

namespace App\Services\Contractors;


interface NotificationsInterfase
{

    /**
     * @param $number
     * @param $title
     * @param $message
     * @return mixed
     */
    public function sendSMS($number, $title, $message);

    /**
     * @param $itemID
     * @param null $newValue
     * @param null $oldValue
     * @param $title
     * @param $url
     * @param $type
     * @return mixed
     */
    public function sendEmail($itemID, $newValue = null, $oldValue = null, $title = null, $url = null, $type);

}