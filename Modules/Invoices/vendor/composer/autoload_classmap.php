<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Modules\\Invoices\\Database\\Seeders\\InvoicesDatabaseSeeder' => $baseDir . '/Database/Seeders/InvoicesDatabaseSeeder.php',
    'Modules\\Invoices\\Datatables\\InvoicesDatatable' => $baseDir . '/Datatables/InvoicesDatatable.php',
    'Modules\\Invoices\\Http\\ApiControllers\\InvoicesApiController' => $baseDir . '/Http/ApiControllers/InvoicesApiController.php',
    'Modules\\Invoices\\Http\\Controllers\\InvoicesController' => $baseDir . '/Http/Controllers/InvoicesController.php',
    'Modules\\Invoices\\Http\\Requests\\CreateInvoicesRequest' => $baseDir . '/Http/Requests/CreateInvoicesRequest.php',
    'Modules\\Invoices\\Http\\Requests\\InvoicesRequest' => $baseDir . '/Http/Requests/InvoicesRequest.php',
    'Modules\\Invoices\\Http\\Requests\\UpdateInvoicesRequest' => $baseDir . '/Http/Requests/UpdateInvoicesRequest.php',
    'Modules\\Invoices\\Models\\Invoices' => $baseDir . '/Models/Invoices.php',
    'Modules\\Invoices\\Policies\\InvoicesPolicy' => $baseDir . '/Policies/InvoicesPolicy.php',
    'Modules\\Invoices\\Presenters\\InvoicesPresenter' => $baseDir . '/Presenters/InvoicesPresenter.php',
    'Modules\\Invoices\\Providers\\InvoicesServiceProvider' => $baseDir . '/Providers/InvoicesServiceProvider.php',
    'Modules\\Invoices\\Repositories\\InvoicesRepository' => $baseDir . '/Repositories/InvoicesRepository.php',
    'Modules\\Invoices\\Transformers\\InvoicesTransformer' => $baseDir . '/Transformers/InvoicesTransformer.php',
);
