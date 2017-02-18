<?php

namespace Modules\Email\;

use App\Providers\AuthServiceProvider;

class EmailAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Email\Models\Email::class => \Modules\Email\Policies\EmailPolicy::class,
    ];
}
