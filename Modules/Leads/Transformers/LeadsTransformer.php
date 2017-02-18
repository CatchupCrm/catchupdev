<?php

namespace Modules\Leads\Transformers;

use Modules\Leads\Models\Leads;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Leads", @SWG\Xml(name="Leads"))
 */

class LeadsTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Leads $leads
     * @return array
     */
    public function transform(Leads $leads)
    {
        return array_merge($this->getDefaults($leads), [
            
            'id' => (int) $leads->public_id,
            'updated_at' => $this->getTimestamp($leads->updated_at),
            'archived_at' => $this->getTimestamp($leads->deleted_at),
        ]);
    }
}
