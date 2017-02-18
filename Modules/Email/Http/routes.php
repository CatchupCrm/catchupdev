<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Email\Http\Controllers'], function()
{
    Route::resource('email', 'EmailController');
    Route::post('email/bulk', 'EmailController@bulk');
    Route::get('api/email', 'EmailController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Email\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('email', 'EmailApiController');
});
