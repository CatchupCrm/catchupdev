<?php

namespace Modules\Expenses\Transformers;

use Modules\Expenses\Models\Expenses;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Expenses", @SWG\Xml(name="Expenses"))
 */

class ExpensesTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="company_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Expenses $expenses
     * @return array
     */
    public function transform(Expenses $expenses)
    {
        return array_merge($this->getDefaults($expenses), [
            
            'id' => (int) $expenses->public_id,
            'updated_at' => $this->getTimestamp($expenses->updated_at),
            'archived_at' => $this->getTimestamp($expenses->deleted_at),
        ]);
    }
}
