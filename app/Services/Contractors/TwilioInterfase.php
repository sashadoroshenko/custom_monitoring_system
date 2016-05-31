<?php

namespace App\Services\Contractors;


interface TwilioInterfase
{

    public function sendSMS();

    public function sendEmail();

}