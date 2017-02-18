<?php

namespace Modules\Products\Transformers;

use Modules\Products\Models\Products;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Products", @SWG\Xml(name="Products"))
 */

class ProductsTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Products $products
     * @return array
     */
    public function transform(Products $products)
    {
        return array_merge($this->getDefaults($products), [
            
            'id' => (int) $products->public_id,
            'updated_at' => $this->getTimestamp($products->updated_at),
            'archived_at' => $this->getTimestamp($products->deleted_at),
        ]);
    }
}
