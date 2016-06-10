<?php

use Carbon\Carbon;

if (!function_exists('showCurrentDateTime')) {

    function showCurrentDateTime($carbon)
    {
        if (!$carbon instanceof Carbon) {
            $carbon = Carbon::parse($carbon);
        }

        $location = "UTC";
        
        if (auth()->user()->location) {
            $location = auth()->user()->location;
        }

        $local = strtotime(Carbon::now()->timezone('UTC'));
        $user = strtotime(Carbon::now()->timezone($location));
        $diff = ($user - $local) / 3600;

        return $carbon->addHours($diff);
    }
}