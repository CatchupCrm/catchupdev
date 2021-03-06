<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Modules\\Tickets\\Database\\Seeders\\TicketsDatabaseSeeder' => $baseDir . '/Database/Seeders/TicketsDatabaseSeeder.php',
    'Modules\\Tickets\\Datatables\\TicketsDatatable' => $baseDir . '/Datatables/TicketsDatatable.php',
    'Modules\\Tickets\\Http\\ApiControllers\\TicketsApiController' => $baseDir . '/Http/ApiControllers/TicketsApiController.php',
    'Modules\\Tickets\\Http\\Controllers\\TicketsController' => $baseDir . '/Http/Controllers/TicketsController.php',
    'Modules\\Tickets\\Http\\Requests\\CreateTicketsRequest' => $baseDir . '/Http/Requests/CreateTicketsRequest.php',
    'Modules\\Tickets\\Http\\Requests\\TicketsRequest' => $baseDir . '/Http/Requests/TicketsRequest.php',
    'Modules\\Tickets\\Http\\Requests\\UpdateTicketsRequest' => $baseDir . '/Http/Requests/UpdateTicketsRequest.php',
    'Modules\\Tickets\\Models\\Tickets' => $baseDir . '/Models/Tickets.php',
    'Modules\\Tickets\\Policies\\TicketsPolicy' => $baseDir . '/Policies/TicketsPolicy.php',
    'Modules\\Tickets\\Presenters\\TicketsPresenter' => $baseDir . '/Presenters/TicketsPresenter.php',
    'Modules\\Tickets\\Providers\\TicketsServiceProvider' => $baseDir . '/Providers/TicketsServiceProvider.php',
    'Modules\\Tickets\\Repositories\\TicketsRepository' => $baseDir . '/Repositories/TicketsRepository.php',
    'Modules\\Tickets\\Transformers\\TicketsTransformer' => $baseDir . '/Transformers/TicketsTransformer.php',
);
