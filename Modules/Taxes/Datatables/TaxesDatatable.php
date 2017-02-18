<?php

namespace Modules\Taxes\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class TaxesDatatable extends EntityDatatable
{
    public $entityType = 'taxes';
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
                mtrans('taxes', 'edit_taxes'),
                function ($model) {
                    return URL::to("taxes/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['taxes', $model->user_id]);
                }
            ],
        ];
    }

}
