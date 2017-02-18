<?php

namespace Modules\Employees\;

use App\Providers\AuthServiceProvider;

class EmployeesAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Employees\Models\Employees::class => \Modules\Employees\Policies\EmployeesPolicy::class,
    ];
}
