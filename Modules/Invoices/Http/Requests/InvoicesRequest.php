<?php

namespace Modules\Invoices\Http\Requests;

use App\Http\Requests\EntityRequest;

class InvoicesRequest extends EntityRequest
{
    protected $entityType = 'invoices';
}
