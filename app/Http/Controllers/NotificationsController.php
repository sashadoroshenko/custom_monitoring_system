<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function search()
    {
        $emails = Notification::where('type', 'email')->where('status', 1)->orderBy('created_at', 'desc')->get();
        $prices = Notification::where('type', 'price')->where('status', 1)->orderBy('created_at', 'desc')->get();
        $stocks = Notification::where('type', 'stock')->where('status', 1)->orderBy('created_at', 'desc')->get();
        $phones = Notification::where('type', 'phone')->where('status', 1)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'emails' => [
                'data' => $emails->take(10),
                'data_count' => $emails->count()
            ],
            'prices' => [
                'data' => $prices->take(10),
                'data_count' => $prices->count()
            ],
            'stocks' => [
                'data' => $stocks->take(10),
                'data_count' => $stocks->count()
            ],
            'phones' => [
                'data' => $phones->take(10),
                'data_count' => $phones->count()
            ]
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
        foreach ($notifications as $notification) {
            $notification->status = 0;
            $notification->save();
        }

        return response()->json(['status' => 200, 'message' => 'Success', 'type' => str_singular($type)]);
    }
}
