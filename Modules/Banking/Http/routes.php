<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Banking\Http\Controllers'], function()
{
    Route::resource('banking', 'BankingController');
    Route::post('banking/bulk', 'BankingController@bulk');
    Route::get('api/banking', 'BankingController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Banking\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('banking', 'BankingApiController');
});
