<?php

namespace Modules\Projects\Http\Requests;

use App\Http\Requests\EntityRequest;

class ProjectsRequest extends EntityRequest
{
    protected $entityType = 'projects';
}
