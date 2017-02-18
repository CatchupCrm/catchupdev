<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Projects\Http\Controllers'], function()
{
    Route::resource('projects', 'ProjectsController');
    Route::post('projects/bulk', 'ProjectsController@bulk');
    Route::get('api/projects', 'ProjectsController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Projects\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('projects', 'ProjectsApiController');
});
