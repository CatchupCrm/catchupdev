<?php

namespace Modules\Taxes\Transformers;

use Modules\Taxes\Models\Taxes;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Taxes", @SWG\Xml(name="Taxes"))
 */

class TaxesTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Taxes $taxes
     * @return array
     */
    public function transform(Taxes $taxes)
    {
        return array_merge($this->getDefaults($taxes), [
            
            'id' => (int) $taxes->public_id,
            'updated_at' => $this->getTimestamp($taxes->updated_at),
            'archived_at' => $this->getTimestamp($taxes->deleted_at),
        ]);
    }
}
