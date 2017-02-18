<?php

namespace Modules\Workflows\Transformers;

use Modules\Workflows\Models\Workflows;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Workflows", @SWG\Xml(name="Workflows"))
 */

class WorkflowsTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Workflows $workflows
     * @return array
     */
    public function transform(Workflows $workflows)
    {
        return array_merge($this->getDefaults($workflows), [
            
            'id' => (int) $workflows->public_id,
            'updated_at' => $this->getTimestamp($workflows->updated_at),
            'archived_at' => $this->getTimestamp($workflows->deleted_at),
        ]);
    }
}
