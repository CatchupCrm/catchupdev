<?php

namespace Modules\BalanceSheet\Http\Requests;

use App\Http\Requests\EntityRequest;

class BalanceSheetRequest extends EntityRequest
{
    protected $entityType = 'balancesheet';
}
