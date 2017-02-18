<?php

namespace Modules\Projects\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class ProjectsDatatable extends EntityDatatable
{
    public $entityType = 'projects';
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
                mtrans('projects', 'edit_projects'),
                function ($model) {
                    return URL::to("projects/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['projects', $model->user_id]);
                }
            ],
        ];
    }

}
