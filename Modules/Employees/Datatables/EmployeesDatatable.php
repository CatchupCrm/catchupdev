<?php

namespace Modules\Employees\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class EmployeesDatatable extends EntityDatatable
{
    public $entityType = 'employees';
    public $sortCol = 1;

    public function columns()
    {
        return [
            
            [
                'created_at',
                function ($model) {
                    return Utils::fromSqlDateTime($model->created_at);
                }
            ],
        ];
    }

    public function actions()
    {
        return [
            [
                mtrans('employees', 'edit_employees'),
                function ($model) {
                    return URL::to("employees/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['employees', $model->user_id]);
                }
            ],
        ];
    }

}
