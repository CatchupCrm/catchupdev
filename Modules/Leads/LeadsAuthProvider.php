<?php

namespace Modules\Leads\;

use App\Providers\AuthServiceProvider;

class LeadsAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Leads\Models\Leads::class => \Modules\Leads\Policies\LeadsPolicy::class,
    ];
}
