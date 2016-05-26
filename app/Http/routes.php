<?php

//\Debugbar::disable();

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



Route::group(['middleware' => ['auth'], 'prefix' => 'home'], function () {

//    Route::get('/', 'ItemsController@index');
    Route::get('/', function () {
        return redirect('items');
//        return view('dashboard');
////        return view('home');
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

//Route::get('dashboard', function(){
//    return view('dashboard');
//});
Route::post('items/showAlert', 'ItemsController@showAlert');
Route::post('items/items', 'ItemsController@items');
Route::resource('items', 'ItemsController');


Route::any('{catchall}', function($page){
//    return 1;
    abort(404,"the page '$page' doesnt exist");
})->where('catchall', '(.*)');