<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Invoices\Http\Controllers'], function()
{
    Route::resource('invoices', 'InvoicesController');
    Route::post('invoices/bulk', 'InvoicesController@bulk');
    Route::get('api/invoices', 'InvoicesController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Invoices\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('invoices', 'InvoicesApiController');
});
