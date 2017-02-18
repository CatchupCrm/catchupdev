<?php

namespace Modules\Leads\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class LeadsDatatable extends EntityDatatable
{
    public $entityType = 'leads';
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
                mtrans('leads', 'edit_leads'),
                function ($model) {
                    return URL::to("leads/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['leads', $model->user_id]);
                }
            ],
        ];
    }

}
