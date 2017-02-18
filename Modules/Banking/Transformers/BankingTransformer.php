<?php

namespace Modules\Banking\Transformers;

use Modules\Banking\Models\Banking;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Banking", @SWG\Xml(name="Banking"))
 */

class BankingTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Banking $banking
     * @return array
     */
    public function transform(Banking $banking)
    {
        return array_merge($this->getDefaults($banking), [
            
            'id' => (int) $banking->public_id,
            'updated_at' => $this->getTimestamp($banking->updated_at),
            'archived_at' => $this->getTimestamp($banking->deleted_at),
        ]);
    }
}
