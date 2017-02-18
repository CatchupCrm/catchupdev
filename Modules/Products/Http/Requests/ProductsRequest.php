<?php

namespace Modules\Products\Http\Requests;

use App\Http\Requests\EntityRequest;

class ProductsRequest extends EntityRequest
{
    protected $entityType = 'products';
}
