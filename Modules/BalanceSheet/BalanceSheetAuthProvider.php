<?php

namespace Modules\BalanceSheet\;

use App\Providers\AuthServiceProvider;

class BalancesheetAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Balancesheet\Models\Balancesheet::class => \Modules\Balancesheet\Policies\BalancesheetPolicy::class,
    ];
}
