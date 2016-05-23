<?php
namespace App\Services;

use App\Services\Contractors\WalmartInterfase;
use GuzzleHttp\Client;

class Walmart implements WalmartInterfase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Walmart constructor.
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => env('WALMART_BASE_URL')]);
    }

    /**
     * Provides price, availability etc of the item
     *
     * @param integer $id
     * @param string $format
     * @return mixed
     */
    public function getItems($ids = null, $upc = null, $lsPublisherId = null, $format = 'json')
    {
        $url = "/v1/items";
        $query = [
            'apiKey' => env('WALMART_API_KEY'),
            'format' => $format
        ];
        if(isset($ids)) {
            $url .= "/" . $ids;
        }
        if(isset($upc)){
            $query['upc'] = $upc;
        }
        if(isset($lsPublisherId)){
            $query['lsPublisherId'] = $lsPublisherId;
        }
        return $this->client->request("GET", $url, [
            'query' => $query
        ]);
    }

    /**
     * Provides reviews of the item
     *
     * @param integer $id
     * @param string $format
     * @return mixed
     */
    public function getReviews($id, $format = 'json')
    {
        return $this->client->request("GET", "/v1/reviews/{$id}", [
            'query' => [
                'format' => $format,
                'apiKey' => env('WALMART_API_KEY')
            ]
        ]);
    }

    /**
     * Allows text search on the Walmart.com catalogue and returns matching items available for sale online
     *
     * @param string $query
     * @param integer $categoryId
     * @param string $facet
     * @param string $filter
     * @param string $range
     * @param string $format
     * @return mixed
     */
    public function getSearch($query, $categoryId = null, $facet = null, $filter = null, $range = null, $format = 'json')
    {
        $query = [
            'query' => $query,
            'format' => $format,
            'apiKey' => env('WALMART_API_KEY')
        ];

        if(isset($categoryId)){
            $query['categoryId'] = $categoryId;
        }

        if($facet != 'off') {
            $query['facet'] = $facet;


            if (isset($filter)) {
                $query['facet.filter'] = "brand:" . $filter;
            }

            if (isset($range)) {
                $query['facet.range'] = "price:[ " . str_replace(" ", " TO ", $range) . " ]";
            }
        }

        return $this->client->request("GET", "/v1/search}", [
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
        // TODO: Implement getVod() method.
    }

    /**
     * Exposes the category taxonomy used by walmart.com to categorize items
     *
     * @param string $format
     * @return mixed
     */
    public function getTaxonomy($format = 'json')
    {
        // TODO: Implement getTaxonomy() method.
    }

    /**
     * Store Locator API
     *
     * @param string $lat
     * @param string $lon
     * @param string $zip
     * @param string $city
     * @param string $format
     * @return mixed
     */
    public function getStoreLocatorAPI($lat = null, $lon = null, $zip = null, $city = null, $format = 'json')
    {
        // TODO: Implement getStoreLocatorAPI() method.
    }

    /**
     * Returns trending items on walmart.com
     *
     * @param string $format
     * @return mixed
     */
    public function getTrendingAPI($format = 'json')
    {
        // TODO: Implement getTrendingAPI() method.
    }

    /**
     * New paginated API to fetch items
     *
     * @param string $category
     * @param string $brand
     * @param string $specialOffer
     * @param string $format
     * @return mixed
     */
    public function getPaginatedAPI($category = null, $brand = null, $specialOffer = null, $format = 'json')
    {
        // TODO: Implement getPaginatedAPI() method.
    }

    /**
     * @param $response
     * @return mixed
     */
    public function getBody($response)
    {
        return $response->getBody()->getContents();
    }
}