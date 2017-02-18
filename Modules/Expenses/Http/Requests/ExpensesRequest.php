<?php

namespace Modules\Expenses\Http\Requests;

use App\Http\Requests\EntityRequest;

class ExpensesRequest extends EntityRequest
{
    protected $entityType = 'expenses';
}
