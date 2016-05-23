<?php
namespace App\Services\Contractors;

interface WalmartInterfase
{

    /**
     * Provides price, availability etc of the item
     *
     * @param null $ids
     * @param null $upc
     * @param null $lsPublisherId
     * @param string $format
     * @return mixed
     */
    public function getItems($ids = null, $upc = null, $lsPublisherId = null, $format = 'json');

    /**
     * Provides reviews of the item
     *
     * @param $id
     * @param null $lsPublisherId
     * @param string $format
     * @return mixed
     */
    public function getReviews($id, $lsPublisherId = null, $format = 'json');

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
    public function getSearch($query, $categoryId = null, $facet = 'off', $filter = null, $range = null, $lsPublisherId = null, $start = 0, $sort = 'relevance', $order = 'asc', $numItems = 10, $format = 'json', $responseGroup = 'base');

    /**
     * Provides Value of the Day on walmart
     *
     * @param string $format
     * @return mixed
     */
    public function getVod($format = 'json');

    /**
     * Exposes the category taxonomy used by walmart.com to categorize items
     * 
     * @param string $format
     * @return mixed
     */
    public function getTaxonomy($format = 'json');

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
    public function getStoreLocatorAPI($lat = null, $lon = null, $zip = null, $city = null, $format = 'json');

    /**
     * Returns trending items on walmart.com
     * 
     * @param null $lsPublisherId
     * @param string $format
     * @return mixed
     */
    public function getTrendingAPI($lsPublisherId = null, $format = 'json');

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
    public function getPaginatedAPI($lsPublisherId = null, $category = null, $brand = null, $specialOffer = null, $format = 'json');

    /**
     * Returns a maximum of 10 item responses
     *
     * @param $itemId
     * @return mixed
     */
    public function getProductRecommendationAPI($itemId);

    /**
     * Returns a maximum of 10 item responses
     * 
     * @param $itemId
     * @return mixed
     */
    public function getPostBrowsedProductsAPI($itemId);

    /**
     * Get the list of Walmart.com products by category.
     * 
     * @param $categoryId
     * @param string $format
     * @return mixed
     */
    public function getDataFeedAPI($categoryId, $format = 'json');

}