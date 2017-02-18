<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Leads\Http\Controllers'], function()
{
    Route::resource('leads', 'LeadsController');
    Route::post('leads/bulk', 'LeadsController@bulk');
    Route::get('api/leads', 'LeadsController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Leads\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('leads', 'LeadsApiController');
});
