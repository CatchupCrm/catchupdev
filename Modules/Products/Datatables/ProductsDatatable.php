<?php

namespace Modules\Products\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class ProductsDatatable extends EntityDatatable
{
    public $entityType = 'products';
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
                mtrans('products', 'edit_products'),
                function ($model) {
                    return URL::to("products/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['products', $model->user_id]);
                }
            ],
        ];
    }

}
