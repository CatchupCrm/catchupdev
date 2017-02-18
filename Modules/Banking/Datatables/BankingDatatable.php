<?php

namespace Modules\Banking\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class BankingDatatable extends EntityDatatable
{
    public $entityType = 'banking';
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
                mtrans('banking', 'edit_banking'),
                function ($model) {
                    return URL::to("banking/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['banking', $model->user_id]);
                }
            ],
        ];
    }

}
