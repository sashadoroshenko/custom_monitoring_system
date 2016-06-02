<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;
use App\Price;
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

        $item = Item::create($data);

        Price::create([
            'item_id' => $item->id,
            'status' => 1,
            'price' => $request->input('price')
        ]);

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

            if ($respons->salePrice != $item->prices()->where('status', 1)->first()->price) {
//            if ($respons->salePrice != 0) {

//                $prices = Price::where('item_id', $item->id)->whereStatus(1)->get();
//
//                foreach ($prices as $price){
//                    $price->status = 0;
//                    $price->save();
//                }
//
//                Price::create([
//                    'item_id' => $item->id,
//                    'status' => 1,
//                    'price' => $respons->salePrice
//                ]);

                $result[] = [
                    'status' => 404,
                    'itemID' => $item->itemID,
                    'oldPrice' => (float)$item->prices()->where('status', 1)->first()->price,
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
        if (isset($items['items'])) {
            foreach ($items['items'] as $item) {
                if (isset($item['marketplace']) && $item['marketplace'] || isset($item['bestMarketplacePrice']) && !$item['bestMarketplacePrice']) {
                    $items['stock'] = "Not Avalible";
                }
                $data[] = $item;
            }
        } else {
            if (isset($items['marketplace']) && $items['marketplace'] || isset($items['bestMarketplacePrice']) && !$items['bestMarketplacePrice']) {
                $items['stock'] = "Not Avalible";
            }
            $data[] = $items;
        }

        return $data;
    }

    public function getPrices($id)
    {
        $prices = Item::findOrFail($id)->prices->sortByDesc('status');
        return view('items.prices', compact('prices'))->render();
    }

    public function updateContent()
    {
        $items = Item::with('prices')->get();
        if ($items->isEmpty()) {
            return [];
        }
        $result = [];
        if ($items->count() > 1) {
            $items = $items->chunk(20);

            foreach ($items as $key => $item) {
                $ids = implode(',', collect($item)->pluck(['itemID'])->all());

                $response = json_decode($this->walmart->getItems($ids)->getBody(), true);

                foreach ($response['items'] as $k => $v) {
                    foreach ($item as $vv) {
                        if ($vv->itemID == $v['itemId']) {

                            //updating price
                            $result['price'][] = $this->getPrice($v, $vv);

                            //updating stock
                            $result['stock'][] = $this->getStock($v, $vv);

                        } else {
                            continue;
                        }
                    }
                }
            }

        } else {
            foreach ($items as $key => $item) {

                $response = json_decode($this->walmart->getItems($item->itemID)->getBody(), true);
                //updating price
                $result['price'] = $this->getPrice($response, $item);

                //updating stock
                $result['stock'] = $this->getStock($response, $item);
            }
        }

        return $result;
    }

    private function getStock($response, $item)
    {
        if (isset($response['marketplace']) && $response['marketplace'] || isset($response['bestMarketplacePrice']) && !$response['bestMarketplacePrice']) {
            $response['stock'] = "Not Avalible";
        }
        $result = [];
//        if ($response['stock'] != $item->stock) {

            $result[] = [
                'status' => 404,
                'id' => $item->id,
                'itemID' => $item->itemID,
                'oldValue' => $item->stock,
                'newValue' => $response['stock']
            ];

            $item->stock = $response['stock'];
            $item->save();
//        }

        return $result;
    }

    private function getPrice($response, $item)
    {
        $result = [];
        if ($item->prices()->where('status', 1)->get()->isEmpty()) {
            Price::create([
                'item_id' => $item->id,
                'status' => 1,
                'price' => $response['salePrice']
            ]);

            $result[] = [
                'status' => 404,
                'id' => $item->id,
                'itemID' => $item->itemID,
                'oldValue' => 0,
                'newValue' => (float)$response['salePrice']
            ];
        }else {
            if ($response['salePrice'] != $item->prices()->where('status', 1)->first()->price) {

                $prices = Price::where('item_id', $item->id)->whereStatus(1)->get();

                foreach ($prices as $price) {
                    $price->status = 0;
                    $price->save();
                }

                $oldPrice = $prices->last()->price;

                Price::create([
                    'item_id' => $item->id,
                    'status' => 1,
                    'price' => $response['salePrice']
                ]);

                $result[] = [
                    'status' => 404,
                    'id' => $item->id,
                    'itemID' => $item->itemID,
                    'oldValue' => (float)$oldPrice,
                    'newValue' => (float)$response['salePrice']
                ];
            }else{
                $prices = Price::where('item_id', $item->id)->whereStatus(1)->get();

                foreach ($prices as $price) {
                    $result[] = [
                        'status' => 404,
                        'id' => $item->id,
                        'itemID' => $item->itemID,
                        'oldValue' => (float)$response['salePrice'],
                        'newValue' => (float)$response['salePrice']
                    ];
                }
            }
        }

        return $result;
    }

}
