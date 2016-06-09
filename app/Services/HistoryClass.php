<?php

namespace App\Services;


use App\Item;
use App\Services\Contractors\HistoryInterface;

class HistoryClass implements HistoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getStockHistories($id)
    {
        $stocks = Item::findOrFail($id)->stocks->sortByDesc('status')->sortByDesc('created_at');
        return view('items.stocks', compact('stocks'))->render();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPriceHistories($id)
    {
        $prices = Item::findOrFail($id)->prices->sortByDesc('status')->sortByDesc('created_at');
        return view('items.prices', compact('prices'))->render();
    }

}