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
    //Route::get('items/sendMail', 'ItemsController@sendMail');
    
    Route::get('twilio', function (\App\Services\Contractors\NotificationsInterfase $notificationsInterfase){
        $number = "+15005550006";
        $message = str_split("http://" . explode('=http://', urldecode("http://c.affil.walmart.com/t/api01?l=http%3A%2F%2Fwww.walmart.com%2Fip%2FOzark-Trail-30-Ounce-Double-Wall-Vacuum-Sealed-Tumbler%2F49772708%3Faffp1%3DgpaSHUa_7RD1lrZUr7vIj0Mt6eORRwuSa3Y5aFkVuwQ%26affilsrc%3Dapi%26veh%3Daff%26wmlspartner%3Dreadonlyapi"))[1], 159);

        return $notificationsInterfase->sendSMS($number, $message);
    });

    Route::post('history/{id}', 'ItemsController@getHistories');
    
    Route::resource('items', 'ItemsController');

    Route::resource('walmart-api-keys', 'WalmartApiKeysController');
    

});

Route::get('test', function (){

    $test = urldecode("http://c.affil.walmart.com/t/api01?l=http%3A%2F%2Fwww.walmart.com%2Fip%2FOzark-Trail-30-Ounce-Double-Wall-Vacuum-Sealed-Tumbler%2F49772708%3Faffp1%3DgpaSHUa_7RD1lrZUr7vIj0Mt6eORRwuSa3Y5aFkVuwQ%26affilsrc%3Dapi%26veh%3Daff%26wmlspartner%3Dreadonlyapi");
    $test = str_split("http://" . explode('=http://', $test)[1], 159);
    dd($test);

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