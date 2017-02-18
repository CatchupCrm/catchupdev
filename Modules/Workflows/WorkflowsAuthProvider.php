<?php

namespace Modules\Workflows\;

use App\Providers\AuthServiceProvider;

class WorkflowsAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Workflows\Models\Workflows::class => \Modules\Workflows\Policies\WorkflowsPolicy::class,
    ];
}
