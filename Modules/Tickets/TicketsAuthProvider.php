<?php

namespace Modules\Tickets\;

use App\Providers\AuthServiceProvider;

class TicketsAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Tickets\Models\Tickets::class => \Modules\Tickets\Policies\TicketsPolicy::class,
    ];
}
