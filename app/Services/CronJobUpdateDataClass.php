<?php

namespace App\Services;

use App\User;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Price;
use App\Services\Contractors\WalmartInterface;
use App\Services\Contractors\NotificationsInterface;
use App\Services\Contractors\CronJobUpdateDataInterface;

class CronJobUpdateDataClass implements CronJobUpdateDataInterface
{
    /**
     * @var WalmartInterface
     */
    protected $walmart;

    /**
     * @var NotificationsInterface
     */
    protected $notification;

    /**
     * UpdateContentClass constructor.
     * @param WalmartInterface $walmartInterface
     * @param NotificationsInterface $notificationsInterface
     */
    public function __construct(WalmartInterface $walmartInterface, NotificationsInterface $notificationsInterface)
    {
        $this->walmart = $walmartInterface;
        $this->notification = $notificationsInterface;
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateContent()
    {

        $items = Item::all();
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
        } else {
            if ($response['stock'] != $item->stocks()->where('status', 1)->first()->stock) {

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
        }

        return $result;
    }


    /**
     * @param $response
     * @param $item
     * @return array
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

        } else {
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

                $result[] = $this->notifications($response, $item, $oldPrice);

            }
        }

        return $result;
    }

    /**
     * @param $response
     * @param $item
     * @param $oldValue
     * @param string $type
     * @param string $search
     * @return array
     */
    public function notifications($response, $item, $oldValue, $type = 'price', $search = 'salePrice')
    {
        $send = true;
        if($response['stock'] == "Not available"){
            $send = false;
        }
        $alerts = Item::all();
        foreach ($alerts as $alert) {
            $url = $alert->url;
            $title = $response['name'];
                if ($alert->alert_email) {
                    if ($alert->itemID == $item->itemID) {
                        $message = "Item with ID <strong>{$item->itemID}</strong> change {$type} Old {$type} {$oldValue} new {$type} {$response[$search]}.";
                        $this->notification->sendEmail($title, $message, $url, $type, $send);

                        foreach(User::all() as $user) {
                            $user->notifications()->create([
                                'status' => 1,
                                'type' => $type,
                                'contact_details' => $user->email,
                                'title' => $title,
                                'content' => $message
                            ]);

                            $user->notifications()->create([
                                'status' => 1,
                                'type' => 'email',
                                'contact_details' => $user->email,
                                'title' => $title,
                                'content' => $message
                            ]);
                        }
                    }
                }

                if ($alert->alert_sms) {
                    if ($alert->itemID == $item->itemID) {
                        $message = "Item with ID {$item->itemID} change {$type} Old {$type} {$oldValue} new {$type} {$response[$search]}. {$url}";
                        $this->notification->sendSMS( $title, $message, $url, $send);

                    }
                }

        }
    }
}