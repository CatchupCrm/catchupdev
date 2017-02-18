<?php

namespace Modules\Core\Http\Requests;

use App\Http\Requests\EntityRequest;

class CoreRequest extends EntityRequest
{
    protected $entityType = 'core';
}
