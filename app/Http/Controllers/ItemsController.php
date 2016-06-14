<?php

namespace App\Http\Controllers;


use Session;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Price;
use App\Models\Stock;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\HistoryClass;
use App\Http\Controllers\Controller;
use App\Services\Contractors\HistoryInterface;
use App\Services\Contractors\UpdateContentInterface;

class ItemsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $items = Item::all();
        $location = "UTC";
        if (auth()->user()->location) {
            $location = auth()->user()->location;
        }

        return view('items.index', compact('items', 'location'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'itemID' => 'required',
//            'userID' => 'required',
            'price' => 'required',
            'url' => 'required',
            'title' => 'required',
            'stock' => 'required'
        ]);

        $data = $request->all();

        $data['user_id'] = auth()->user()->id;

        if (!$request->has('alert_desktop')) {
            $data['alert_desktop'] = 0;
        }

        if (!$request->has('alert_email')) {
            $data['alert_email'] = 0;
        }

        if (!$request->has('alert_sms')) {
            $data['alert_sms'] = 0;
        }

        $item = Item::create($data);

        Price::create([
            'item_id' => $item->id,
            'status' => 1,
            'price' => $request->input('price')
        ]);

        Stock::create([
            'item_id' => $item->id,
            'status' => 1,
            'stock' => $request->input('stock')
        ]);

        Session::flash('flash_message', 'Item added!');

        return redirect('items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);

        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'itemID' => 'required',
//            'userID' => 'required',
            'price' => 'required',
            'url' => 'required',
            'title' => 'required',
            'stock' => 'required'
        ]);

        $item = Item::findOrFail($id);
        $data = $request->all();

        $data['user_id'] = auth()->user()->id;

        if (!$request->has('alert_desktop') && $item->alert_desktop || !$request->has('alert_desktop') && !$item->alert_desktop) {
            $data['alert_desktop'] = 0;
        }

        if (!$request->has('alert_email') && $item->alert_email || !$request->has('alert_email') && !$item->alert_email) {
            $data['alert_email'] = 0;
        }

        if (!$request->has('alert_sms') && $item->alert_sms || !$request->has('alert_sms') && !$item->alert_sms) {
            $data['alert_sms'] = 0;
        }

        $item->update($data);

        Session::flash('flash_message', 'Item updated!');

        return redirect('items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        Item::destroy($id);

        Session::flash('flash_message', 'Item deleted!');

        return redirect('items');
    }

    /**
     * @param UpdateContentInterface $updateContentInterface
     * @return mixed
     */
    public function items(UpdateContentInterface $updateContentInterface)
    {
        return $updateContentInterface->getItem(request()->input('id', null));
    }

    /**
     * @param HistoryClass $historyClass
     * @return string
     */
    public function getHistories(HistoryInterface $historyInterface)
    {
        if (request()->input('type') == 'stock') {
            return $historyInterface->getStockHistories(request()->input('id'));
        }

        return $historyInterface->getPriceHistories(request()->input('id'));
    }

    /**
     * @param UpdateContentInterface $updateContentInterface
     * @return mixed
     */
    public function updateContent(UpdateContentInterface $updateContentInterface)
    {
        return $updateContentInterface->updateContent();
    }

}
