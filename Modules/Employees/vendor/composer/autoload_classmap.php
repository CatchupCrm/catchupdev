<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Modules\\Employees\\Database\\Seeders\\EmployeesDatabaseSeeder' => $baseDir . '/Database/Seeders/EmployeesDatabaseSeeder.php',
    'Modules\\Employees\\Datatables\\EmployeesDatatable' => $baseDir . '/Datatables/EmployeesDatatable.php',
    'Modules\\Employees\\Http\\ApiControllers\\EmployeesApiController' => $baseDir . '/Http/ApiControllers/EmployeesApiController.php',
    'Modules\\Employees\\Http\\Controllers\\EmployeesController' => $baseDir . '/Http/Controllers/EmployeesController.php',
    'Modules\\Employees\\Http\\Requests\\CreateEmployeesRequest' => $baseDir . '/Http/Requests/CreateEmployeesRequest.php',
    'Modules\\Employees\\Http\\Requests\\EmployeesRequest' => $baseDir . '/Http/Requests/EmployeesRequest.php',
    'Modules\\Employees\\Http\\Requests\\UpdateEmployeesRequest' => $baseDir . '/Http/Requests/UpdateEmployeesRequest.php',
    'Modules\\Employees\\Models\\Employees' => $baseDir . '/Models/Employees.php',
    'Modules\\Employees\\Policies\\EmployeesPolicy' => $baseDir . '/Policies/EmployeesPolicy.php',
    'Modules\\Employees\\Presenters\\EmployeesPresenter' => $baseDir . '/Presenters/EmployeesPresenter.php',
    'Modules\\Employees\\Providers\\EmployeesServiceProvider' => $baseDir . '/Providers/EmployeesServiceProvider.php',
    'Modules\\Employees\\Repositories\\EmployeesRepository' => $baseDir . '/Repositories/EmployeesRepository.php',
    'Modules\\Employees\\Transformers\\EmployeesTransformer' => $baseDir . '/Transformers/EmployeesTransformer.php',
);
