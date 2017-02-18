<?php

namespace Modules\Relations\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class RelationsDatatable extends EntityDatatable
{
    public $entityType = 'relations';
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
                mtrans('relations', 'edit_relations'),
                function ($model) {
                    return URL::to("relations/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['relations', $model->user_id]);
                }
            ],
        ];
    }

}
