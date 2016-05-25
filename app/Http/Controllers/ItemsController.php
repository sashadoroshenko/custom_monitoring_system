<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;
use App\Services\Contractors\WalmartInterfase;
use Illuminate\Http\Request;
use Session;

class ItemsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $items = Item::paginate(15);

        return view('items.index', compact('items'));
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
        $this->validate($request, ['parentItemId' => 'required', 'name' => 'required', 'salePrice' => 'required', 'upc' => 'required', 'categoryPath' => 'required', 'shortDescription' => 'required', 'longDescription' => 'required', 'brandName' => 'required', 'thumbnailImage' => 'required', 'mediumImage' => 'required', 'largeImage' => 'required', 'productTrackingUrl' => 'required', 'ninetySevenCentShipping' => 'required', 'standardShipRate' => 'required', 'size' => 'required', 'color' => 'required', 'marketplace' => 'required', 'shipToStore' => 'required', 'freeShipToStore' => 'required', 'productUrl' => 'required', 'variants' => 'required', 'categoryNode' => 'required', 'bundle' => 'required', 'clearance' => 'required', 'preOrder' => 'required', 'stock' => 'required', 'attribute' => 'required', 'gender' => 'required', 'addToCartUrl' => 'required', 'affiliateAddToCartUrl' => 'required', 'freeShippingOver50Dollars' => 'required', 'maxItemsInOrder' => 'required', 'giftOptions' => 'required', 'availableOnline' => 'required',]);

        Item::create($request->all());

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
        $this->validate($request, ['parentItemId' => 'required', 'name' => 'required', 'salePrice' => 'required', 'upc' => 'required', 'categoryPath' => 'required', 'shortDescription' => 'required', 'longDescription' => 'required', 'brandName' => 'required', 'thumbnailImage' => 'required', 'mediumImage' => 'required', 'largeImage' => 'required', 'productTrackingUrl' => 'required', 'ninetySevenCentShipping' => 'required', 'standardShipRate' => 'required', 'size' => 'required', 'color' => 'required', 'marketplace' => 'required', 'shipToStore' => 'required', 'freeShipToStore' => 'required', 'productUrl' => 'required', 'variants' => 'required', 'categoryNode' => 'required', 'bundle' => 'required', 'clearance' => 'required', 'preOrder' => 'required', 'stock' => 'required', 'attribute' => 'required', 'gender' => 'required', 'addToCartUrl' => 'required', 'affiliateAddToCartUrl' => 'required', 'freeShippingOver50Dollars' => 'required', 'maxItemsInOrder' => 'required', 'giftOptions' => 'required', 'availableOnline' => 'required',]);

        $item = Item::findOrFail($id);
        $item->update($request->all());

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

    public function showAlert()
    {
        $items = Item::all();
        return $items;
        foreach ($items as $item) {
            $respons = json_decode($this->walmart->getItems($item->ItemID)->getBody());
            if ($respons->salePrice != $item->price) {
                \Log::error('error');
            } else {
                \Log::info('success');
            }
        }
    }
}
