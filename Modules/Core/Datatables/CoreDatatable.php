<?php

namespace Modules\Core\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class CoreDatatable extends EntityDatatable
{
    public $entityType = 'core';
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
                mtrans('core', 'edit_core'),
                function ($model) {
                    return URL::to("core/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['core', $model->user_id]);
                }
            ],
        ];
    }

}
