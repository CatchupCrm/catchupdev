<?php

namespace Modules\BalanceSheet\Transformers;

use Modules\Balancesheet\Models\Balancesheet;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Balancesheet", @SWG\Xml(name="Balancesheet"))
 */

class BalancesheetTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Balancesheet $balancesheet
     * @return array
     */
    public function transform(Balancesheet $balancesheet)
    {
        return array_merge($this->getDefaults($balancesheet), [
            
            'id' => (int) $balancesheet->public_id,
            'updated_at' => $this->getTimestamp($balancesheet->updated_at),
            'archived_at' => $this->getTimestamp($balancesheet->deleted_at),
        ]);
    }
}
