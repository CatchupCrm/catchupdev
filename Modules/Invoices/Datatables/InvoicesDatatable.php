<?php

namespace Modules\Invoices\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class InvoicesDatatable extends EntityDatatable
{
    public $entityType = 'invoices';
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
                mtrans('invoices', 'edit_invoices'),
                function ($model) {
                    return URL::to("invoices/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['invoices', $model->user_id]);
                }
            ],
        ];
    }

}
