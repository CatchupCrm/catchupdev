<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Relations\Http\Controllers'], function()
{
    Route::resource('relations', 'RelationsController');
    Route::post('relations/bulk', 'RelationsController@bulk');
    Route::get('api/relations', 'RelationsController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Relations\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('relations', 'RelationsApiController');
});
