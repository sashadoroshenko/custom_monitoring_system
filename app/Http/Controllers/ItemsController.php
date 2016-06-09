<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;
use App\Price;
use App\Services\Contractors\NotificationsInterfase;
use App\Services\Contractors\WalmartInterfase;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class ItemsController extends Controller
{
    /**
     * @var WalmartInterfase
     */
    protected $walmart;

    /**
     * @var NotificationsInterfase
     */
    protected $notifications;

    /**
     * HomeController constructor.
     * @param WalmartInterfase $walmartInterfase
     * @param NotificationsInterfase $notificationsInterfase
     */
    public function __construct(WalmartInterfase $walmartInterfase, NotificationsInterfase $notificationsInterfase)
    {
        $this->walmart = $walmartInterfase;
        $this->notifications = $notificationsInterfase;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $items = Item::all();
        $location = "UTC";
        if(auth()->user()->location){
            $location = auth()->user()->location;
        }

        return view('items.index', compact('items', 'location'));
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
     * @return void
     */
    public function destroy($id)
    {
        Item::destroy($id);

        Session::flash('flash_message', 'Item deleted!');

        return redirect('items');
    }

    /**
     * @return array
     */
    public function showDesktopAlerts()
    {
        $items = Item::where('alert_desktop', 1)->get();
        if (count($items) == 0) {
            return [];
        }
        $result = [];
        foreach ($items as $item) {

            $response = $this->walmart->getItems($item->itemID);
            if ($response->getStatusCode() != 200) {
                return response()->json(['message' => $response->getReasonPhrase(), 'code' => $response->getStatusCode()]);
            }
            $response = json_decode($response->getBody());

            if ($response->salePrice != $item->prices()->where('status', 1)->first()->price) {
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
                    'newPrice' => (float)$response->salePrice
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
        $response = $this->walmart->getItems(request()->input('id', null));
        if ($response->getStatusCode() != 200) {
            return response()->json(['message' => $response->getReasonPhrase(), 'code' => $response->getStatusCode()]);
        }
        $items = json_decode($response->getBody(), true);

        if (isset($items['items'])) {
            foreach ($items['items'] as $item) {
                if (isset($item['marketplace']) && $item['marketplace'] || isset($item['bestMarketplacePrice']) && !$item['bestMarketplacePrice']) {
                    $items['stock'] = "Not Available";
                    $items['salePrice'] = 0;
                }
                $data[] = $item;
            }
        } else {
            if (isset($items['marketplace']) && $items['marketplace'] || isset($items['bestMarketplacePrice']) && !$items['bestMarketplacePrice']) {
                $items['stock'] = "Not Available";
                $items['salePrice'] = 0;
            }
            $data[] = $items;
        }

        return $data;
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    public function getHistories($id)
    {
        if (request()->input('type') == 'stock') {
            $stocks = Item::findOrFail($id)->stocks->sortByDesc('status')->sortByDesc('created_at');
            return view('items.stocks', compact('stocks'))->render();
        }

        $prices = Item::findOrFail($id)->prices->sortByDesc('status')->sortByDesc('created_at');
        return view('items.prices', compact('prices'))->render();
    }

    /**
     * @return array
     */
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

                $response = $this->walmart->getItems($ids);
                if ($response->getStatusCode() != 200) {
                    return response()->json(['message' => $response->getReasonPhrase(), 'code' => $response->getStatusCode()]);
                }

                $response = json_decode($response->getBody(), true);

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

                $response = $this->walmart->getItems($item->itemID);
                if ($response->getStatusCode() != 200) {
                    return response()->json(['message' => $response->getReasonPhrase(), 'code' => $response->getStatusCode()]);
                }

                $response = json_decode($response->getBody(), true);
                //updating price
                $result['price'] = $this->getPrice($response, $item);

                //updating stock
                $result['stock'] = $this->getStock($response, $item);
            }
        }

        return $result;
    }

    /**
     * @param $response
     * @param $item
     * @return array
     */
    private function getStock($response, $item)
    {
        if (isset($response['marketplace']) && $response['marketplace'] || isset($response['bestMarketplacePrice']) && !$response['bestMarketplacePrice']) {
            $response['salePrice'] = 0;
            $response['stock'] = "Not Available";
        }
        $result = [];

        if ($item->stocks()->where('status', 1)->get()->isEmpty()) {
            Stock::create([
                'item_id' => $item->id,
                'status' => 1,
                'stock' => $response['stock']
            ]);

            $result[] = [
                'status' => 404,
                'id' => $item->id,
                'desktopAlert' => false,
                'itemID' => $item->itemID,
                'oldValue' => 0,
                'newValue' => $response['stock'],
                'lastUpdated' => $item->updated_at,
                'updated' => false
            ];
        } else {
            if ($response['stock'] != $item->stocks()->where('status', 1)->first()->stock) {

                $item = Item::findOrFail($item->id);
                $item->updated_at = Carbon::now();
                $item->save();

                $stocks = Stock::where('item_id', $item->id)->whereStatus(1)->get();

                foreach ($stocks as $stock) {
                    $stock->status = 0;
                    $stock->save();
                }

                $oldStock = $stocks->last()->stock;

                Stock::create([
                    'item_id' => $item->id,
                    'status' => 1,
                    'stock' => $response['stock']
                ]);

                $result[] = $this->notifications($response, $item, $oldStock, 'stock', 'stock');

            } 
//            else {
//                $stocks = Stock::where('item_id', $item->id)->whereStatus(1)->get();
//
//                foreach ($stocks as $stock) {
//                    $result[] = [
//                        'status' => 404,
//                        'id' => $item->id,
//                        'desktopAlert' => false,
//                        'itemID' => $item->itemID,
//                        'oldValue' => $response['stock'],
//                        'newValue' => $response['stock'],
//                        'lastUpdated' => $item->updated_at,
//                        'updated' => false
//                    ];
//                }
//            }
        }

        return $result;
    }

    /**
     * @param $response
     * @param $item
     * @return array
     */
    private function getPrice($response, $item)
    {
        if (isset($response['marketplace']) && $response['marketplace'] || isset($response['bestMarketplacePrice']) && !$response['bestMarketplacePrice']) {
            $response['salePrice'] = 0;
            $response['stock'] = "Not Available";
        }
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
                'desktopAlert' => false,
                'itemID' => $item->itemID,
                'oldValue' => 0,
                'newValue' => (float)$response['salePrice'],
                'lastUpdated' => $item->updated_at,
                'updated' => false
            ];
        } else {
            if ($response['salePrice'] != $item->prices()->where('status', 1)->first()->price) {

                $item = Item::findOrFail($item->id);
                $item->updated_at = Carbon::now();
                $item->save();

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

                $result[] = $this->notifications($response, $item, $oldPrice);

            }
//            else {
//                $prices = Price::where('item_id', $item->id)->whereStatus(1)->get();
//
//                foreach ($prices as $price) {
//                    $result[] = [
//                        'status' => 404,
//                        'id' => $item->id,
//                        'desktopAlert' => false,
//                        'itemID' => $item->itemID,
//                        'oldValue' => (float)$response['salePrice'],
//                        'newValue' => (float)$response['salePrice'],
//                        'lastUpdated' => $item->updated_at,
//                        'updated' => false
//                    ];
//                }
//            }
        }

        return $result;
    }

    private function notifications($response, $item, $oldValue, $type = 'price', $search = 'salePrice')
    {
        $desktopAlert = false;
        $alerts = Item::all();
        foreach ($alerts as $alert) {
            $url = "http://" . explode('=http://', urldecode($response['productUrl']))[1];
            $title = $response['name'];
            if ($alert->alert_email) {
                if ($alert->itemID == $item->itemID && $response['stock'] != "Not Available" && $response['stock'] != "Not available") {
                    $this->notifications->sendEmail($item->itemID, $response[$search], $oldValue, $title, $url, $type);
                }
            }

            if ($alert->alert_sms) {
                if ($alert->itemID == $item->itemID && $response['stock'] != "Not Available" && $response['stock'] != "Not available") {
                    $message = "Item with ID {$item->itemID} change {$type}. Old {$type} {$oldValue} new {$type} {$response[$search]}. {$url}";
                    $this->notifications->sendSMS(env('TWILIO_NUMBER_TO'), $message);
                }
            }

            if ($alert->alert_desktop) {
                if ($alert->itemID == $item->itemID && $response['stock'] != "Not Available" && $response['stock'] != "Not available") {
                    $desktopAlert = true;
                }
            }
        }

        return [
            'status' => 404,
            'id' => $item->id,
            'desktopAlert' => $desktopAlert,
            'itemID' => $item->itemID,
            'oldValue' => $type == 'price' ? (float)$oldValue : $oldValue,
            'newValue' => $type == 'price' ? (float)$response[$search] : $response[$search],
            'lastUpdated' => $item->updated_at,
            'updated' => true
        ];

    }
}
