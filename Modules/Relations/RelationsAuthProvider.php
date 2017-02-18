<?php

namespace Modules\Relations\;

use App\Providers\AuthServiceProvider;

class RelationsAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Relations\Models\Relations::class => \Modules\Relations\Policies\RelationsPolicy::class,
    ];
}
