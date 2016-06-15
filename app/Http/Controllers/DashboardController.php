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
        $items = Item::all();

        $product_list = Item::with(['prices' => function ($query) {
            $query->where('price', '>', 0);
            $query->where('status', 1);
        }])->orderBy('created_at', 'desc')->get();

        $array = [];

        foreach ($product_list as $price) {
            if ($price->prices->count() > 0) {
                $array[] = $price;
            }
        }

        $product_list = collect($array)->take(3)->all();


        $last_list = Price::with(['item' => function ($query) {
            $query->with(['stocks' => function ($query) {
                $query->where('status', 1);
            }]);
        }])->orderBy('created_at', 'desc')->where('status', 1)->where('price', '>', 0)->take(3)->get();


        return view('dashboard', compact('users_count', 'logs_count', 'items', 'keys_count', 'product_list', 'last_list'));
    }

}