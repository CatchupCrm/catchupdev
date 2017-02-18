<?php

namespace Modules\Tickets\Transformers;

use Modules\Tickets\Models\Tickets;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Tickets", @SWG\Xml(name="Tickets"))
 */

class TicketsTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="company_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Tickets $tickets
     * @return array
     */
    public function transform(Tickets $tickets)
    {
        return array_merge($this->getDefaults($tickets), [
            
            'id' => (int) $tickets->public_id,
            'updated_at' => $this->getTimestamp($tickets->updated_at),
            'archived_at' => $this->getTimestamp($tickets->deleted_at),
        ]);
    }
}
