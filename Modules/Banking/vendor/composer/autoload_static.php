<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteebf0e558292f75b66ac90d28c71cd06
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Banking\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Banking\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Modules\\Banking\\Database\\Seeders\\BankingDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/BankingDatabaseSeeder.php',
        'Modules\\Banking\\Datatables\\BankingDatatable' => __DIR__ . '/../..' . '/Datatables/BankingDatatable.php',
        'Modules\\Banking\\Http\\ApiControllers\\BankingApiController' => __DIR__ . '/../..' . '/Http/ApiControllers/BankingApiController.php',
        'Modules\\Banking\\Http\\Controllers\\BankingController' => __DIR__ . '/../..' . '/Http/Controllers/BankingController.php',
        'Modules\\Banking\\Http\\Requests\\BankingRequest' => __DIR__ . '/../..' . '/Http/Requests/BankingRequest.php',
        'Modules\\Banking\\Http\\Requests\\CreateBankingRequest' => __DIR__ . '/../..' . '/Http/Requests/CreateBankingRequest.php',
        'Modules\\Banking\\Http\\Requests\\UpdateBankingRequest' => __DIR__ . '/../..' . '/Http/Requests/UpdateBankingRequest.php',
        'Modules\\Banking\\Models\\Banking' => __DIR__ . '/../..' . '/Models/Banking.php',
        'Modules\\Banking\\Policies\\BankingPolicy' => __DIR__ . '/../..' . '/Policies/BankingPolicy.php',
        'Modules\\Banking\\Presenters\\BankingPresenter' => __DIR__ . '/../..' . '/Presenters/BankingPresenter.php',
        'Modules\\Banking\\Providers\\BankingServiceProvider' => __DIR__ . '/../..' . '/Providers/BankingServiceProvider.php',
        'Modules\\Banking\\Repositories\\BankingRepository' => __DIR__ . '/../..' . '/Repositories/BankingRepository.php',
        'Modules\\Banking\\Transformers\\BankingTransformer' => __DIR__ . '/../..' . '/Transformers/BankingTransformer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteebf0e558292f75b66ac90d28c71cd06::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteebf0e558292f75b66ac90d28c71cd06::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticIniteebf0e558292f75b66ac90d28c71cd06::$classMap;

        }, null, ClassLoader::class);
    }
}
