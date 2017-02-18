<?php

namespace Modules\Workflows\Http\Requests;

use App\Http\Requests\EntityRequest;

class WorkflowsRequest extends EntityRequest
{
    protected $entityType = 'workflows';
}
