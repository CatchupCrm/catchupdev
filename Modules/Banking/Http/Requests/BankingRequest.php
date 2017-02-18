<?php

namespace Modules\Banking\Http\Requests;

use App\Http\Requests\EntityRequest;

class BankingRequest extends EntityRequest
{
    protected $entityType = 'banking';
}
