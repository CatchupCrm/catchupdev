<?php

namespace Modules\Projects\;

use App\Providers\AuthServiceProvider;

class ProjectsAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Projects\Models\Projects::class => \Modules\Projects\Policies\ProjectsPolicy::class,
    ];
}
