<?php

namespace Modules\Relations\Http\Requests;

use App\Http\Requests\EntityRequest;

class RelationsRequest extends EntityRequest
{
    protected $entityType = 'relations';
}
