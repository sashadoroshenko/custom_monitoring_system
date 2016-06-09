<?php

namespace App\Services;

use App\Item;
use App\Stock;
use App\Price;
use Carbon\Carbon;
use App\Services\Contractors\WalmartInterfase;
use App\Services\Contractors\UpdateContentInterface;
use App\Services\Contractors\NotificationsInterfase;

class UpdateContentClass implements UpdateContentInterface
{
    /**
     * @var WalmartInterfase
     */
    protected $walmart;

    /**
     * @var NotificationsInterfase
     */
    protected $notification;

    /**
     * @var string
     */
    protected $location;

    /**
     * UpdateContentClass constructor.
     * @param WalmartInterfase $walmartInterfase
     * @param NotificationsInterfase $notificationsInterfase
     */
    public function __construct(WalmartInterfase $walmartInterfase, NotificationsInterfase $notificationsInterfase)
    {
        $this->walmart = $walmartInterfase;
        $this->notification = $notificationsInterfase;
        $this->location = "UTC";
        if(auth()->user()->location){
            $this->location = auth()->user()->location;
        }
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getItem($id = null)
    {
        $data = [];
        $response = $this->walmart->getItems($id);
        if ($response->getStatusCode() != 200) {
            return response()->json(['message' => $response->getReasonPhrase(), 'code' => $response->getStatusCode()]);
        }
        $items = json_decode($response->getBody(), true);

        if (isset($items['items'])) {
            foreach ($items['items'] as $item) {
                if (isset($item['marketplace']) && $item['marketplace'] || isset($item['bestMarketplacePrice']) && !$item['bestMarketplacePrice']) {
                    $items['stock'] = "Not available";
                    $items['salePrice'] = 0;
                }
                $data[] = $item;
            }
        } else {
            if (isset($items['marketplace']) && $items['marketplace'] || isset($items['bestMarketplacePrice']) && !$items['bestMarketplacePrice']) {
                $items['stock'] = "Not available";
                $items['salePrice'] = 0;
            }
            $data[] = $items;
        }

        return $data;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getStock($response, $item)
    {
        if (isset($response['marketplace']) && $response['marketplace'] || isset($response['bestMarketplacePrice']) && !$response['bestMarketplacePrice']) {
            $response['salePrice'] = 0;
            $response['stock'] = "Not available";
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
                'lastUpdated' => $item->updated_at->timezone($this->location),
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
     * @return mixed
     */
    public function getPrice($response, $item)
    {
        if (isset($response['marketplace']) && $response['marketplace'] || isset($response['bestMarketplacePrice']) && !$response['bestMarketplacePrice']) {
            $response['salePrice'] = 0;
            $response['stock'] = "Not available";
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
                'lastUpdated' => $item->updated_at->timezone($this->location),
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

    /**
     * @param $response
     * @param $item
     * @param $oldValue
     * @param string $type
     * @param string $search
     * @return mixed
     */
    public function notifications($response, $item, $oldValue, $type = 'price', $search = 'salePrice')
    {
        $desktopAlert = false;
        $alerts = Item::all();
        foreach ($alerts as $alert) {
            $url = "http://" . explode('=http://', urldecode($response['productUrl']))[1];
            $title = $response['name'];
            if ($alert->alert_email) {
                if ($alert->itemID == $item->itemID && $response['stock'] != "Not Available" && $response['stock'] != "Not available") {
                    $this->notification->sendEmail($item->itemID, $response[$search], $oldValue, $title, $url, $type);
                }
            }

            if ($alert->alert_sms) {
                if ($alert->itemID == $item->itemID && $response['stock'] != "Not Available" && $response['stock'] != "Not available") {
                    $message = "Item with ID {$item->itemID} change {$type}. Old {$type} {$oldValue} new {$type} {$response[$search]}. {$url}";
                    $this->notification->sendSMS(env('TWILIO_NUMBER_TO'), $message);
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
            'lastUpdated' => $item->updated_at->timezone($this->location),
            'updated' => true
        ];
    }

}