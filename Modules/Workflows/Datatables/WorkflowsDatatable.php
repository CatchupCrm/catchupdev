<?php

namespace Modules\Workflows\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class WorkflowsDatatable extends EntityDatatable
{
    public $entityType = 'workflows';
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
                mtrans('workflows', 'edit_workflows'),
                function ($model) {
                    return URL::to("workflows/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['workflows', $model->user_id]);
                }
            ],
        ];
    }

}
