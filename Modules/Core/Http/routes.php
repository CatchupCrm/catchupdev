<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Core\Http\Controllers'], function()
{
    Route::resource('core', 'CoreController');
    Route::post('core/bulk', 'CoreController@bulk');
    Route::get('api/core', 'CoreController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Core\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('core', 'CoreApiController');
});
