<?php

namespace Modules\Banking\;

use App\Providers\AuthServiceProvider;

class BankingAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Banking\Models\Banking::class => \Modules\Banking\Policies\BankingPolicy::class,
    ];
}
