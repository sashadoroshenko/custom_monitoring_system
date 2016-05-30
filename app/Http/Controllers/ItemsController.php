<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;
use App\Services\Contractors\WalmartInterfase;
use Illuminate\Http\Request;
use Session;

class ItemsController extends Controller
{
    /**
     * @var WalmartInterfase
     */
    protected $walmart;

    /**
     * HomeController constructor.
     * @param WalmartInterfase $walmartInterfase
     */
    public function __construct(WalmartInterfase $walmartInterfase)
    {
        $this->walmart = $walmartInterfase;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $items = Item::paginate(15);

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'itemID' => 'required',
//            'userID' => 'required',
            'price' => 'required',
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

        Item::create($data);

        Session::flash('flash_message', 'Item added!');

        return redirect('items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return void
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
     * @return void
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
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'itemID' => 'required',
//            'userID' => 'required',
            'price' => 'required',
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
     * @return void
     */
    public function destroy($id)
    {
        Item::destroy($id);

        Session::flash('flash_message', 'Item deleted!');

        return redirect('items');
    }

    public function showDesktopAlerts()
    {
        $items = Item::where('alert_desktop', 1)->get();
        if (count($items) == 0) {
            return [];
        }
        $result = [];
        foreach ($items as $item) {
            $respons = json_decode($this->walmart->getItems($item->itemID)->getBody());

            if ($respons->salePrice != $item->price) {
                $result[] = [
                    'status' => 404,
                    'itemID' => $item->itemID,
                    'oldPrice' => (float)$item->price,
                    'newPrice' => (float)$respons->salePrice
                ];
            }
        }

        return $result;
    }


    /**
     * Provides price, availability etc of the item
     *
     */

    public function items()
    {
        $data = [];
        $items = json_decode($this->walmart->getItems(request()->input('id', null))->getBody(), true);
        if(isset($items['items'])) {
            foreach ($items['items'] as $item) {
                if (isset($item['marketplace']) && !$item['marketplace'] || isset($item['bestMarketplacePrice']) && $item['bestMarketplacePrice']) {
                    $data[] = $item;
                }
            }
        }else{
            if (isset($items['marketplace']) && !$items['marketplace'] || isset($items['bestMarketplacePrice']) && $items['bestMarketplacePrice']) {
                $data[] = $items;
            }
        }

        return $data;
    }
}
