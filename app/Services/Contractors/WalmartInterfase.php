<?php
namespace App\Services\Contractors;

interface WalmartInterfase
{

    public function getItems($ids, $upc, $lsPublisherId, $format);

//    public function getReviews($id, $lsPublisherId = null, $format = 'json');

    public function getSearch(
        $query,
        $categoryId = null,

//        $lsPublisherId = null,
//        $start = 0,
//        $sort = 'relevance',
//        $order = 'asc',
//        $numItems = 10,
//        $responseGroup = 'base',

        $facet = 'off',
        $filter = null,
        $range = null,
        $format = 'json'
    );

    public function getVod($format = 'json');

    public function getTaxonomy($format = 'json');

    public function getStoreLocatorAPI($lat = null, $lon = null, $zip = null, $city = null, $format = 'json');

    public function getTrendingAPI($format = 'json');

    public function getPaginatedAPI($category = null, $brand = null, $specialOffer = null, $format = 'json');

}