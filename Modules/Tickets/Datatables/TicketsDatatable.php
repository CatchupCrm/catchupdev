<?php

namespace Modules\Tickets\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class TicketsDatatable extends EntityDatatable
{
    public $entityType = 'tickets';
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
                mtrans('tickets', 'edit_tickets'),
                function ($model) {
                    return URL::to("tickets/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['tickets', $model->user_id]);
                }
            ],
        ];
    }

}
