<?php

namespace Modules\Taxes\Http\Requests;

use App\Http\Requests\EntityRequest;

class TaxesRequest extends EntityRequest
{
    protected $entityType = 'taxes';
}
