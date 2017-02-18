<?php

namespace Modules\Expenses\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class ExpensesDatatable extends EntityDatatable
{
    public $entityType = 'expenses';
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
                mtrans('expenses', 'edit_expenses'),
                function ($model) {
                    return URL::to("expenses/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['expenses', $model->user_id]);
                }
            ],
        ];
    }

}
