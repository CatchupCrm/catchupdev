<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Expenses\Http\Controllers'], function()
{
    Route::resource('expenses', 'ExpensesController');
    Route::post('expenses/bulk', 'ExpensesController@bulk');
    Route::get('api/expenses', 'ExpensesController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Expenses\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('expenses', 'ExpensesApiController');
});
