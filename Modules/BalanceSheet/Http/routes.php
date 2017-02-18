<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\BalanceSheet\Http\Controllers'], function()
{
    Route::resource('balancesheet', 'BalanceSheetController');
    Route::post('balancesheet/bulk', 'BalanceSheetController@bulk');
    Route::get('api/balancesheet', 'BalanceSheetController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\BalanceSheet\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('balancesheet', 'BalanceSheetApiController');
});
