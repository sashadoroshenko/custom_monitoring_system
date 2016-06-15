<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Price;
use App\Models\Stock;
use App\Models\WalmartApiKey;
use App\User;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class DashboardController extends Controller
{
    public function index()
    {
        $users_count = User::all()->count();
        $logs_count = count(LaravelLogViewer::all());
        $keys_count = WalmartApiKey::all()->count();
        $items = Item::all()->count();

        $product_list = Item::with(['prices' => function ($query) {
            $query->where('price', '>', 0);
            $query->where('status', 1);
        }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($item) {
                return $item->prices->count() > 0;
            })
            ->take(5)
            ->all();


        $last_list = Item::with(['prices' => function ($query) {
            $query->where('price', '>', 0);
            $query->where('status', 1);
        }, 'stocks' => function ($query) {
//            $query->where('price', '>', 0);
            $query->where('status', 1);
        }])
            ->get()
            ->filter(function ($item) {
                return $item->prices->count() > 0 || ($item->prices->count() == 0 && $item->stocks[0]->stock != "Not available");
            })
            ->take(5)
            ->all();

//        dd($last_list);

        return view('dashboard', compact('users_count', 'logs_count', 'items', 'keys_count', 'product_list', 'last_list'));
    }

}