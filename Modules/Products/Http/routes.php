<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Products\Http\Controllers'], function()
{
    Route::resource('products', 'ProductsController');
    Route::post('products/bulk', 'ProductsController@bulk');
    Route::get('api/products', 'ProductsController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Products\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('products', 'ProductsApiController');
});
