<?php

namespace Modules\Employees\Http\Requests;

use App\Http\Requests\EntityRequest;

class EmployeesRequest extends EntityRequest
{
    protected $entityType = 'employees';
}
