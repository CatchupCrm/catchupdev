<?php

namespace Modules\Relations\Transformers;

use Modules\Relations\Models\Relations;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Relations", @SWG\Xml(name="Relations"))
 */

class RelationsTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="company_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Relations $relations
     * @return array
     */
    public function transform(Relations $relations)
    {
        return array_merge($this->getDefaults($relations), [
            
            'id' => (int) $relations->public_id,
            'updated_at' => $this->getTimestamp($relations->updated_at),
            'archived_at' => $this->getTimestamp($relations->deleted_at),
        ]);
    }
}
