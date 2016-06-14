<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Stock;
use App\Models\Price;
use App\User;
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
     * @var User
     */
    protected $user;

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
     * @param User $user
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateContent(User $user)
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
                            $result['price'][] = $this->getPrice($user, $v, $vv);

                            //updating stock
                            $result['stock'][] = $this->getStock($user, $v, $vv);

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
                $result['price'] = $this->getPrice($user, $response, $item);

                //updating stock
                $result['stock'] = $this->getStock($user, $response, $item);
            }
        }

        return $result;
    }


    /**
     * @param User $user
     * @param $response
     * @param $item
     * @return array
     */
    public function getStock(User $user, $response, $item)
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

                $result[] = $this->notifications($user, $response, $item, $oldStock, 'stock', 'stock');

            }
        }

        return $result;
    }


    /**
     * @param User $user
     * @param $response
     * @param $item
     * @return array
     */
    public function getPrice(User $user, $response, $item)
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

                $result[] = $this->notifications($user, $response, $item, $oldPrice);
                
            }
        }

        return $result;
    }

    /**
     * @param User $user
     * @param $response
     * @param $item
     * @param $oldValue
     * @param string $type
     * @param string $search
     * @return array
     */
    public function notifications(User $user, $response, $item, $oldValue, $type = 'price', $search = 'salePrice')
    {
        $alerts = Item::all();
        foreach ($alerts as $alert) {
            $url = $alert->url;
            $title = $response['name'];
            $message = "Item with ID <strong>{$item->itemID}</strong> change {$type} Old {$type} {$oldValue} new {$type} {$response[$search]}.";
            if ($alert->alert_email) {
                if ($alert->itemID == $item->itemID) {
                    $this->notification->sendEmail($user, $title, $message, $url, $type);
                }
            }

            if ($alert->alert_sms) {
                if ($alert->itemID == $item->itemID) {
                    $this->notification->sendSMS($user, $title, $message, $url);
                }
            }
        }
    }

}