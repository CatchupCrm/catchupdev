<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Tickets\Http\Controllers'], function()
{
    Route::resource('tickets', 'TicketsController');
    Route::post('tickets/bulk', 'TicketsController@bulk');
    Route::get('api/tickets', 'TicketsController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Tickets\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('tickets', 'TicketsApiController');
});
