<?php

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');



Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'home'], function () {

        Route::get('/', function () {
            return redirect('items');
        });

        Route::any('item', 'HomeController@items');

        Route::any('reviews', 'HomeController@reviews');

        Route::any('search', 'HomeController@search');

        Route::get('vod', 'HomeController@getVod');

        Route::any('stores', 'HomeController@stores');

        Route::get('taxonomy', 'HomeController@taxonomy');

        Route::get('trends', 'HomeController@trendings');

        Route::get('paginate', 'HomeController@getPaginate');

        Route::any('recommendation', 'HomeController@recommendation');

        Route::any('postBrowsed', 'HomeController@postBrowsed');

        Route::any('dataFeed', 'HomeController@dataFeed');

    });

    Route::post('items/showDesktopAlerts', 'ItemsController@showDesktopAlerts');
    Route::post('items/updateContent', 'ItemsController@updateContent');

    Route::post('items/items', 'ItemsController@items');
    Route::get('items/sendMail', 'ItemsController@sendMail');

    Route::post('history/{id}', 'ItemsController@getHistories');
    
    Route::resource('items', 'ItemsController');
    

});

Route::get('test', function (){
    $collection = collect([
        ['product_id' => 'prod-100', 'name' => 'Desk'],
        ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]);

    $plucked = $collection->pluck('name');

    $plucked->all();

    dd($plucked);
});

Route::any('{catchall}', function($page){
//    return 1;
    abort(404,"the page '$page' doesnt exist");
})->where('catchall', '(.*)');