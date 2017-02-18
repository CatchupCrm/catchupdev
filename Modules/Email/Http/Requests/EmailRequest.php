<?php

namespace Modules\Email\Http\Requests;

use App\Http\Requests\EntityRequest;

class EmailRequest extends EntityRequest
{
    protected $entityType = 'email';
}
