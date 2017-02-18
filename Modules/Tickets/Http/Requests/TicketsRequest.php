<?php

namespace Modules\Tickets\Http\Requests;

use App\Http\Requests\EntityRequest;

class TicketsRequest extends EntityRequest
{
    protected $entityType = 'tickets';
}
