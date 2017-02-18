<?php

namespace Modules\Taxes\;

use App\Providers\AuthServiceProvider;

class TaxesAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Taxes\Models\Taxes::class => \Modules\Taxes\Policies\TaxesPolicy::class,
    ];
}
