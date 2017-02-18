<?php

namespace Modules\Products\;

use App\Providers\AuthServiceProvider;

class ProductsAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Products\Models\Products::class => \Modules\Products\Policies\ProductsPolicy::class,
    ];
}
