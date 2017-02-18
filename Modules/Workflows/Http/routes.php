<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Workflows\Http\Controllers'], function()
{
    Route::resource('workflows', 'WorkflowsController');
    Route::post('workflows/bulk', 'WorkflowsController@bulk');
    Route::get('api/workflows', 'WorkflowsController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Workflows\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('workflows', 'WorkflowsApiController');
});
