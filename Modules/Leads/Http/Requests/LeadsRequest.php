<?php

namespace Modules\Leads\Http\Requests;

use App\Http\Requests\EntityRequest;

class LeadsRequest extends EntityRequest
{
    protected $entityType = 'leads';
}
