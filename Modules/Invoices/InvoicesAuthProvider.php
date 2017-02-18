<?php

namespace Modules\Invoices\;

use App\Providers\AuthServiceProvider;

class InvoicesAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Invoices\Models\Invoices::class => \Modules\Invoices\Policies\InvoicesPolicy::class,
    ];
}
