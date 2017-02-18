<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Modules\\Taxes\\Database\\Seeders\\TaxesDatabaseSeeder' => $baseDir . '/Database/Seeders/TaxesDatabaseSeeder.php',
    'Modules\\Taxes\\Datatables\\TaxesDatatable' => $baseDir . '/Datatables/TaxesDatatable.php',
    'Modules\\Taxes\\Http\\ApiControllers\\TaxesApiController' => $baseDir . '/Http/ApiControllers/TaxesApiController.php',
    'Modules\\Taxes\\Http\\Controllers\\TaxesController' => $baseDir . '/Http/Controllers/TaxesController.php',
    'Modules\\Taxes\\Http\\Requests\\CreateTaxesRequest' => $baseDir . '/Http/Requests/CreateTaxesRequest.php',
    'Modules\\Taxes\\Http\\Requests\\TaxesRequest' => $baseDir . '/Http/Requests/TaxesRequest.php',
    'Modules\\Taxes\\Http\\Requests\\UpdateTaxesRequest' => $baseDir . '/Http/Requests/UpdateTaxesRequest.php',
    'Modules\\Taxes\\Models\\Taxes' => $baseDir . '/Models/Taxes.php',
    'Modules\\Taxes\\Policies\\TaxesPolicy' => $baseDir . '/Policies/TaxesPolicy.php',
    'Modules\\Taxes\\Presenters\\TaxesPresenter' => $baseDir . '/Presenters/TaxesPresenter.php',
    'Modules\\Taxes\\Providers\\TaxesServiceProvider' => $baseDir . '/Providers/TaxesServiceProvider.php',
    'Modules\\Taxes\\Repositories\\TaxesRepository' => $baseDir . '/Repositories/TaxesRepository.php',
    'Modules\\Taxes\\Transformers\\TaxesTransformer' => $baseDir . '/Transformers/TaxesTransformer.php',
);
