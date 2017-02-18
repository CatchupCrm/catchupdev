<?php

namespace Modules\Email\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class EmailDatatable extends EntityDatatable
{
    public $entityType = 'email';
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
                mtrans('email', 'edit_email'),
                function ($model) {
                    return URL::to("email/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['email', $model->user_id]);
                }
            ],
        ];
    }

}
