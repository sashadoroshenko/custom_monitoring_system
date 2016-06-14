<?php

namespace App\Http\Controllers;

use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return mixed
     */
    public function show()
    {
        $profile = auth()->user();
        return view('profile.view', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $profile = auth()->user();
        $locs = \DateTimeZone::listIdentifiers();
        $locations = [];
        foreach ($locs as $location){
            $locations[$location] = $location;
        }
        return view('profile.edit', compact('profile', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'location' => 'required'
        ]);

        auth()->user()->update($request->all());

        Session::flash('flash_message', 'Profile updated!');

        return redirect('profile');
    }
}
