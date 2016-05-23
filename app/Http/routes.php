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


Route::group(['middleware' => ['auth'], 'prefix' => 'home'], function () {

    Route::get('/', function(){
        return view('home');
    });

    Route::any('items', 'HomeController@items');

    Route::any('reviews', 'HomeController@reviews');

    Route::any('search', 'HomeController@search');

    Route::get('vod', 'HomeController@getVod');

    Route::any('stores', 'HomeController@stores');

    Route::get('trends', 'HomeController@trendings');

    Route::get('paginate', 'HomeController@getPaginate');

    Route::any('recommendation', 'HomeController@recommendation');

    Route::any('postBrowsed', 'HomeController@postBrowsed');

    Route::any('dataFeed', 'HomeController@dataFeed');

});


