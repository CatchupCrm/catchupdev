<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc931f434e405e67984da12af9db51ed6
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Core\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Modules\\Core\\Database\\Seeders\\CoreDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/CoreDatabaseSeeder.php',
        'Modules\\Core\\Datatables\\CoreDatatable' => __DIR__ . '/../..' . '/Datatables/CoreDatatable.php',
        'Modules\\Core\\Http\\ApiControllers\\CoreApiController' => __DIR__ . '/../..' . '/Http/ApiControllers/CoreApiController.php',
        'Modules\\Core\\Http\\Controllers\\CoreController' => __DIR__ . '/../..' . '/Http/Controllers/CoreController.php',
        'Modules\\Core\\Http\\Requests\\CoreRequest' => __DIR__ . '/../..' . '/Http/Requests/CoreRequest.php',
        'Modules\\Core\\Http\\Requests\\CreateCoreRequest' => __DIR__ . '/../..' . '/Http/Requests/CreateCoreRequest.php',
        'Modules\\Core\\Http\\Requests\\UpdateCoreRequest' => __DIR__ . '/../..' . '/Http/Requests/UpdateCoreRequest.php',
        'Modules\\Core\\Models\\Core' => __DIR__ . '/../..' . '/Models/Core.php',
        'Modules\\Core\\Policies\\CorePolicy' => __DIR__ . '/../..' . '/Policies/CorePolicy.php',
        'Modules\\Core\\Presenters\\CorePresenter' => __DIR__ . '/../..' . '/Presenters/CorePresenter.php',
        'Modules\\Core\\Providers\\CoreServiceProvider' => __DIR__ . '/../..' . '/Providers/CoreServiceProvider.php',
        'Modules\\Core\\Repositories\\CoreRepository' => __DIR__ . '/../..' . '/Repositories/CoreRepository.php',
        'Modules\\Core\\Transformers\\CoreTransformer' => __DIR__ . '/../..' . '/Transformers/CoreTransformer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc931f434e405e67984da12af9db51ed6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc931f434e405e67984da12af9db51ed6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc931f434e405e67984da12af9db51ed6::$classMap;

        }, null, ClassLoader::class);
    }
}