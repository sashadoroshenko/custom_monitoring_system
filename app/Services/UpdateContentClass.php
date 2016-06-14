<?php

namespace App\Services;

use App\Models\Item;
use App\Services\Contractors\WalmartInterface;
use App\Services\Contractors\UpdateContentInterface;
use Carbon\Carbon;

class UpdateContentClass implements UpdateContentInterface
{
    /**
     * @var WalmartInterface
     */
    protected $walmart;

    /**
     * UpdateContentClass constructor.
     * @param WalmartInterface $walmartInterface
     */
    public function __construct(WalmartInterface $walmartInterface)
    {
        $this->walmart = $walmartInterface;
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
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateContent()
    {
        $items = Item::with(['prices' => function ($query) {
//            $query->where('prices.updated_at', ">", 'prices.created_at');
            $query->where('prices.updated_at', '>', Carbon::now()->addMinute(-1));
            $query->where('prices.status', 1);
        },
            'stocks' => function ($query) {
//                $query->where('stocks.updated_at', ">", 'stocks.created_at');
                $query->where('stocks.updated_at', '>', Carbon::now()->addMinute(-1));
                $query->where('stocks.status', 1);
            }])
            ->get();

        $result = [
            'price' => [],
            'stock' => []
        ];

        foreach ($items as $item) {
            if (count($item->prices) > 0 && $item->alert_desktop) {
                foreach ($item->prices as $price) {
                    $price->desktop_alerts = false;
                    if ($item->stocks()->where('status', 1)->first()->stock != "Not Available" && $item->stocks()->where('status', 1)->first()->stock != "Not available") {
                        $price->desktop_alerts = true;
                    }
                    $price->updated_at = showCurrentDateTime($price->updated_at);
                    $result['price'][] = $price;
                }
            }
            if (count($item->stocks) > 0 && $item->alert_desktop) {
                foreach ($item->stocks as $stock) {
                    $stock->desktop_alerts = true;
                    $stock->updated_at = showCurrentDateTime($stock->updated_at);
                    $result['stock'][] = $stock;
                }
            }
        }
        return $result;
    }


}