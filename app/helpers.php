<?php

use Carbon\Carbon;

function showCurrentDateTime(Carbon $carbon){
    $location = "UTC";
    if(auth()->user()->location){
        $location = auth()->user()->location;
    }
    $local = strtotime(Carbon::now()->timezone('UTC'));
    $user = strtotime(Carbon::now()->timezone($location));
    $diff = ($user - $local) / 3600;

    return $carbon->addHours($diff);
}