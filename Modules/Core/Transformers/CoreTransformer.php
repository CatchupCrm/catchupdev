<?php

namespace Modules\Core\Transformers;

use Modules\Core\Models\Core;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Core", @SWG\Xml(name="Core"))
 */

class CoreTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="company_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Core $core
     * @return array
     */
    public function transform(Core $core)
    {
        return array_merge($this->getDefaults($core), [
            
            'id' => (int) $core->public_id,
            'updated_at' => $this->getTimestamp($core->updated_at),
            'archived_at' => $this->getTimestamp($core->deleted_at),
        ]);
    }
}
