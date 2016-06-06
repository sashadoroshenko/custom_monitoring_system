<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\WalmartApiKey;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class WalmartApiKeysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $walmartapikeys = WalmartApiKey::paginate(15);

        return view('walmartapikeys.index', compact('walmartapikeys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('walmartapikeys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['key' => 'required', ]);

        WalmartApiKey::create($request->all());

        Session::flash('flash_message', 'WalmartApiKey added!');

        return redirect('walmart-api-keys');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $walmartapikey = WalmartApiKey::findOrFail($id);

        return view('walmartapikeys.show', compact('walmartapikey'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $walmartapikey = WalmartApiKey::findOrFail($id);

        return view('walmartapikeys.edit', compact('walmartapikey'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['key' => 'required', ]);

        $walmartapikey = WalmartApiKey::findOrFail($id);
        $walmartapikey->update($request->all());

        Session::flash('flash_message', 'WalmartApiKey updated!');

        return redirect('walmart-api-keys');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        WalmartApiKey::destroy($id);

        Session::flash('flash_message', 'WalmartApiKey deleted!');

        return redirect('walmart-api-keys');
    }
}
