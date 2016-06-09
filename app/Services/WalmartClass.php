<?php
namespace App\Services;

use App\WalmartApiKey;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Services\Contractors\WalmartInterfase;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Response;

class WalmartClass implements WalmartInterfase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected static $key;

    /**
     * @var integer
     */
    protected static $count;

    /**
     * Walmart constructor.
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => env('WALMART_BASE_URL')]);
        self::$key = is_null(WalmartApiKey::get()->first()) ? env('WALMART_API_KEY') : WalmartApiKey::get()->first()->key;
        self::$count = WalmartApiKey::get()->count();
    }

    /**
     * Provides price, availability etc of the item
     *
     * @param null $ids
     * @param null $upc
     * @param null $lsPublisherId
     * @param string $format
     * @return mixed
     */
    public function getItems($ids = null, $upc = null, $lsPublisherId = null, $format = 'json')
    {
        if (is_null($ids) && is_null($upc)) {
            return false;
        }

        $url = "/v1/items";

        $query = [
            'apiKey' => self::$key,
            'format' => $format
        ];

        if (isset($ids) && !empty($ids)) {
            if (count(preg_split("/[\s,-]+/", $ids)) > 1) {
                $query['ids'] = implode(',', preg_split("/[\s,-]+/", $ids));
            } else {
                $url .= "/" . $ids;
            }
        }

        if (isset($upc) && !empty($upc)) {
            $query['upc'] = $upc;
        }

        if (isset($lsPublisherId) && !empty($lsPublisherId)) {
            $query['lsPublisherId'] = $lsPublisherId;
        }

        $params = [
            'query' => $query
        ];

        return $this->error_hendler($url, $params, "GET");
//
//        try {
//            $result = $this->client->request("GET", $url, [
//                'query' => $query
//            ]);
//        } catch (ClientException $e) {
//            $query = [
//                'apiKey' => WalmartApiKey::get()->first()->key
//            ];
//            $result = $this->client->request("GET", $url, [
//                'query' => $query
//            ]);
//        }
//
//        return $result;

    }

    /**
     * Provides reviews of the item
     *
     * @param $id
     * @param null $lsPublisherId
     * @param string $format
     * @return mixed
     */
    public function getReviews($id, $lsPublisherId = null, $format = 'json')
    {
        $url = "/v1/reviews/{$id}";

        $query = [
            'apiKey' => env('WALMART_API_KEY'),
            'format' => $format
        ];

        if (isset($lsPublisherId) && !empty($lsPublisherId)) {
            $query['lsPublisherId'] = $lsPublisherId;
        }

        return $this->client->request("GET", $url, [
            'query' => $query
        ]);
    }

    /**
     * Allows text search on the Walmart.com catalogue and returns matching items available for sale online
     *
     * @param $query
     * @param null $categoryId
     * @param string $facet
     * @param null $filter
     * @param null $range
     * @param null $lsPublisherId
     * @param int $start
     * @param string $sort
     * @param string $order
     * @param int $numItems
     * @param string $format
     * @param string $responseGroup
     * @return mixed
     */
    public function getSearch($query, $categoryId = null, $facet = 'off', $filter = null, $range = null, $lsPublisherId = null, $start = 0, $sort = 'relevance', $order = 'asc', $numItems = 10, $format = 'json', $responseGroup = 'base')
    {
        $query = [
            'query' => $query,
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        if (isset($lsPublisherId) && !empty($lsPublisherId)) {
            $query['lsPublisherId'] = $lsPublisherId;
        }

        if (isset($categoryId) && !empty($categoryId)) {
            $query['categoryId'] = $categoryId;
        }

        if ($facet != 'off') {
            $query['facet'] = $facet;


            if (isset($filter) && !empty($filter)) {
                $query['facet.filter'] = "brand:" . $filter;
            }

            if (isset($range) && !empty($range)) {
                $query['facet.range'] = "price:[ " . str_replace(" ", " TO ", $range) . " ]";
            }
        }

        return $this->client->request("GET", "/v1/search", [
            'query' => $query
        ]);
    }

    /**
     * Provides Value of the Day on walmart
     *
     * @param string $format
     * @return mixed
     */
    public function getVod($format = 'json')
    {
        $query = [
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        return $this->client->request("GET", "/v1/vod", [
            'query' => $query
        ]);
    }

    /**
     * Exposes the category taxonomy used by walmart.com to categorize items
     *
     * @param string $format
     * @return mixed
     */
    public function getTaxonomy($format = 'json')
    {
        $query = [
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        return $this->client->request("GET", "/v1/taxonomy", [
            'query' => $query
        ]);
    }

    /**
     * Store Locator API
     *
     * @param null $lat
     * @param null $lon
     * @param null $zip
     * @param null $city
     * @param string $format
     * @return mixed
     */
    public function getStoreLocatorAPI($lat = null, $lon = null, $zip = null, $city = null, $format = 'json')
    {
        $query = [
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        if (isset($lat) && !empty($lat)) {
            $query['lat'] = $lat;
        }

        if (isset($lon) && !empty($lon)) {
            $query['lon'] = $lon;
        }

        if (isset($zip) && !empty($zip)) {
            $query['zip'] = $zip;
        }

        if (isset($city) && !empty($city)) {
            $query['city'] = $city;
        }

        return $this->client->request("GET", "/v1/stores", [
            'query' => $query
        ]);
    }

    /**
     * Returns trending items on walmart.com
     *
     * @param null $lsPublisherId
     * @param string $format
     * @return mixed
     */
    public function getTrendingAPI($lsPublisherId = null, $format = 'json')
    {
        $query = [
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        if (isset($lsPublisherId) && !empty($lsPublisherId)) {
            $query['lsPublisherId'] = $lsPublisherId;
        }

        return $this->client->request("GET", "/v1/trends", [
            'query' => $query
        ]);
    }

    /**
     * New paginated API to fetch items
     *
     * @param null $lsPublisherId
     * @param null $category
     * @param null $brand
     * @param null $specialOffer
     * @param string $format
     * @return mixed
     */
    public function getPaginatedAPI($lsPublisherId = null, $category = null, $brand = null, $specialOffer = null, $format = 'json')
    {
        $query = [
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        if (isset($lsPublisherId) && !empty($lsPublisherId)) {
            $query['lsPublisherId'] = $lsPublisherId;
        }

        if (isset($category) && !empty($category)) {
            $query['category'] = $category;
        }

        if (isset($brand) && !empty($brand)) {
            $query['brand'] = $brand;
        }

        if (isset($specialOffer) && !empty($specialOffer)) {
            $query['specialOffer'] = $specialOffer;
        }

        return $this->client->request("GET", "/v1/paginated/items", [
            'query' => $query
        ]);
    }

    /**
     * Returns a maximum of 10 item responses
     *
     * @param $itemId
     * @return mixed
     */
    public function getProductRecommendationAPI($itemId)
    {
        $query = [
            'itemId' => $itemId,
            'apiKey' => env('WALMART_API_KEY')
        ];

        return $this->client->request("GET", "/v1/nbp", [
            'query' => $query
        ]);
    }

    /**
     * Returns a maximum of 10 item responses
     *
     * @param $itemId
     * @return mixed
     */
    public function getPostBrowsedProductsAPI($itemId)
    {
        $query = [
            'itemId' => $itemId,
            'apiKey' => env('WALMART_API_KEY')
        ];

        return $this->client->request("GET", "/v1/postbrowse", [
            'query' => $query
        ]);
    }

    /**
     * Get the list of Walmart.com products by category.
     *
     * @param $categoryId
     * @param string $format
     * @return mixed
     */
    public function getDataFeedAPI($categoryId, $format = 'json')
    {
        $query = [
            'format' => $format,
            'categoryId' => $categoryId,
            'apiKey' => env('WALMART_API_KEY')
        ];

        return $this->client->request("GET", "/v1/feeds/items", [
            'query' => $query
        ]);
    }

    /**
     * @param $url
     * @param $params
     * @param string $method
     * @return mixed|null|\Psr\Http\Message\ResponseInterface
     */
    private function error_hendler($url, $params, $method = "GET")
    {
        try {
            $result = $this->client->request($method, $url, $params);
        } catch (ClientException $e) {
            $id = WalmartApiKey::whereKey(self::$key)->first()->id;
            if (!self::$count--) {
                return $e->getResponse();
            }
            self::$key = WalmartApiKey::where('id', '>', $id)->first()->key;
            $params['query']['apiKey'] = self::$key;
            return $this->error_hendler($url, $params, $method);
        }
        return $result;
    }
}