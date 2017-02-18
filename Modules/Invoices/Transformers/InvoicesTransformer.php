<?php

namespace Modules\Invoices\Transformers;

use Modules\Invoices\Models\Invoices;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Invoices", @SWG\Xml(name="Invoices"))
 */

class InvoicesTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Invoices $invoices
     * @return array
     */
    public function transform(Invoices $invoices)
    {
        return array_merge($this->getDefaults($invoices), [
            
            'id' => (int) $invoices->public_id,
            'updated_at' => $this->getTimestamp($invoices->updated_at),
            'archived_at' => $this->getTimestamp($invoices->deleted_at),
        ]);
    }
}
