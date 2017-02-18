<?php

namespace Modules\Core\;

use App\Providers\AuthServiceProvider;

class CoreAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Core\Models\Core::class => \Modules\Core\Policies\CorePolicy::class,
    ];
}
