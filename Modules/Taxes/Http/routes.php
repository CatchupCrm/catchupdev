<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Taxes\Http\Controllers'], function()
{
    Route::resource('taxes', 'TaxesController');
    Route::post('taxes/bulk', 'TaxesController@bulk');
    Route::get('api/taxes', 'TaxesController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Taxes\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('taxes', 'TaxesApiController');
});
