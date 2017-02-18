<?php

namespace Modules\Employees\Transformers;

use Modules\Employees\Models\Employees;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Employees", @SWG\Xml(name="Employees"))
 */

class EmployeesTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Employees $employees
     * @return array
     */
    public function transform(Employees $employees)
    {
        return array_merge($this->getDefaults($employees), [
            
            'id' => (int) $employees->public_id,
            'updated_at' => $this->getTimestamp($employees->updated_at),
            'archived_at' => $this->getTimestamp($employees->deleted_at),
        ]);
    }
}
