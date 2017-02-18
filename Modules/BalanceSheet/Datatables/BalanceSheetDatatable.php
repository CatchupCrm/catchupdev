<?php

namespace Modules\BalanceSheet\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class BalanceSheetDatatable extends EntityDatatable
{
    public $entityType = 'balancesheet';
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
                mtrans('balancesheet', 'edit_balancesheet'),
                function ($model) {
                    return URL::to("balancesheet/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['balancesheet', $model->user_id]);
                }
            ],
        ];
    }

}
