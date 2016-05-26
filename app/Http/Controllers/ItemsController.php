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
        $this->validate($request, [
            'itemID' => 'required',
            'userID' => 'required',
            'price' => 'required',
            'title' => 'required',
            'stock' => 'required'
        ]);

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
        $this->validate($request, [
            'itemID' => 'required',
            'userID' => 'required',
            'price' => 'required',
            'title' => 'required',
            'stock' => 'required'
        ]);

        $item = Item::findOrFail($id);
        $data = $request->all();

        if (!$request->has('alert_desktop') && $item->alert_desktop) {
            $data['alert_desktop'] = false;
        }

        if (!$request->has('alert_email') && $item->alert_email) {
            $data['alert_email'] = false;
        }

        if (!$request->has('alert_sms') && $item->alert_sms) {
            $data['alert_sms'] = false;
        }

        $item->update($data);

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
        if (count($items) == 0) {
            return response()->json(['status' => 'error']);
        }
        $result = [];
        foreach ($items as $item) {
            $respons = json_decode($this->walmart->getItems($item->itemID)->getBody());
            if ($respons->salePrice != $item->price) {
                $result[] = [
                    'status' => 404,
                    'itemID' => $item->itemID,
                    'oldPrice' => (float)$item->price,
                    'newPrice' => (float)$respons->salePrice
                ];
            } else {
                $result[] = [
                    'status' => 200,
                    'itemID' => $item->itemID,
                    'oldPrice' => $item->price,
                    'newPrice' => $respons->salePrice
                ];
            }
        }

        return $result;
    }


    /**
     * Provides price, availability etc of the item
     *
     */

    public function items()
    {
        return $this->walmart->getItems(intval(request()->input('id', null)))->getBody();
    }
}
