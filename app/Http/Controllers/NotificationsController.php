<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function search()
    {
        $emails = Notification::where('type', 'email')->where('status', 1)->get();
        $prices = Notification::where('type', 'price')->where('status', 1)->get();
        $stocks = Notification::where('type', 'stock')->where('status', 1)->get();
        $phones = Notification::where('type', 'phone')->where('status', 1)->get();

        return response()->json([
            'emails' => $emails,
            'prices' => $prices,
            'stocks' => $stocks,
            'phones' => $phones
        ]);
    }

    public function index($type)
    {
        $notifications = Notification::where('type', str_singular($type))->orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();

        return view('notifications.index', compact('notifications'));
    }

    public function show($type, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->status = 0;
        $notification->save();
        return view('notifications.show', compact('notification'));
    }

    public function update($type)
    {
        $notifications = Notification::where('type', str_singular($type))->get();
        foreach ($notifications as $notification){
            $notification->status = 0;
            $notification->save();
        }
        
        return response()->json(['status' => 200, 'message' => 'Success', 'type' => str_singular($type)]);
    }
}
