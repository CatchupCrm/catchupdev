<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Employees\Http\Controllers'], function()
{
    Route::resource('employees', 'EmployeesController');
    Route::post('employees/bulk', 'EmployeesController@bulk');
    Route::get('api/employees', 'EmployeesController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Employees\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('employees', 'EmployeesApiController');
});
