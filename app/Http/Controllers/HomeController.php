<?php

namespace App\Http\Controllers;

use App\Services\Contractors\WalmartInterfase;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
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
     * Provides price, availability etc of the item
     *
     */
    public function items()
    {
        if (request()->isMethod('POST')) {
            $ids = request()->input('ids', null);
            $upc = request()->input('upc', null);
            $lsPublisherId = request()->input('lsPublisherId', null);

            $response = json_decode($this->walmart->getItems($ids, $upc, $lsPublisherId)->getBody());
            return view('items', compact('response', 'ids', 'upc', 'lsPublisherId'));
        }
        return view('items');
    }

    /**
     * Provides reviews of the item
     *
     */
    public function reviews()
    {
        if (request()->isMethod('POST')) {
            $id = request()->input('id', null);
            $lsPublisherId = request()->input('lsPublisherId', null);
            $response = json_decode($this->walmart->getReviews($id, $lsPublisherId)->getBody());
            return view('reviews', compact('response', 'ids', 'upc', 'lsPublisherId'));
        }
        return view('reviews');
    }

    /**
     * Allows text search on the Walmart.com catalogue and returns matching items available for sale online
     *
     */
    public function search()
    {
        if (request()->isMethod('POST')) {
            $query = request()->input('query');
            $lsPublisherId = request()->input('lsPublisherId', null);
            $categoryId = request()->input('categoryId', null);
            $facet = request()->input('facet', 'off');
            $filter = request()->input('filter', null);
            $range = request()->input('range', null);

            $response = json_decode($this->walmart->getSearch($query, $categoryId, $facet, $filter, $range, $lsPublisherId)->getBody());
            return view('search', compact('response', 'query', 'categoryId', 'facet', 'filter', 'range'));
        }
        return view('search');
    }


    /**
     *Provides Value of the Day on walmart
     */
    public function getVod()
    {
        $response = json_decode($this->walmart->getVod()->getBody());
        return view('vod', compact('response'));
    }

    /**
     *Exposes the category taxonomy used by walmart.com to categorize items
     */
    public function taxonomy()
    {
        $response = json_decode($this->walmart->getTaxonomy()->getBody());
        return view('taxonomy', compact('response'));
    }

    /**
     *Store Locator API
     */
    public function stores()
    {
        if (request()->isMethod('POST')) {
            $lat = request()->input('lat', null);
            $lon = request()->input('lon', null);
            $zip = request()->input('zip', null);
            $city = request()->input('city', null);
            $response = json_decode($this->walmart->getStoreLocatorAPI($lat, $lon, $zip, $city)->getBody());
            return view('stores', compact('response', 'lat', 'lon', 'zip', 'city'));
        }
        return view('stores');
    }

    /**
     *Returns trending items on walmart.com
     */
    public function trendings()
    {
        $response = json_decode($this->walmart->getTrendingAPI()->getBody());
        return view('trends', compact('response'));
    }

    /**
     *New paginated API to fetch items
     */
    public function getPaginate()
    {
        $response = json_decode($this->walmart->getPaginatedAPI()->getBody());
        return view('paginate', compact('response'));
    }

    /**
     * Returns a maximum of 10 item responses
     *
     */
    public function recommendation()
    {
        if (request()->isMethod('POST')) {
            $itemId = request()->input('itemId');
            $response = json_decode($this->walmart->getProductRecommendationAPI($itemId)->getBody());
            return view('recommendation', compact('response', 'itemId'));
        }
        return view('recommendation');
    }

    /**
     * Returns a maximum of 10 item responses
     *
     */
    public function postBrowsed()
    {
        if (request()->isMethod('POST')) {
            $itemId = request()->input('itemId');
            $response = json_decode($this->walmart->getPostBrowsedProductsAPI($itemId)->getBody());
            return view('postBrowsed', compact('response', 'itemId'));
        }

        return view('postBrowsed');
    }

    /**
     * Get the list of Walmart.com products by category.
     *
     */
    public function dataFeed()
    {
        if (request()->isMethod('POST')) {
            $categoryId = request()->input('categoryId');
            $response = json_decode($this->walmart->getDataFeedAPI($categoryId)->getBody());
            return view('dataFeed', compact('response', 'categoryId'));
        }

        return view('dataFeed');
    }
}