<?php

namespace Modules\Expenses\;

use App\Providers\AuthServiceProvider;

class ExpensesAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Expenses\Models\Expenses::class => \Modules\Expenses\Policies\ExpensesPolicy::class,
    ];
}
