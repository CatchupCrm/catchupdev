<?php

namespace Modules\Projects\Transformers;

use Modules\Projects\Models\Projects;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Projects", @SWG\Xml(name="Projects"))
 */

class ProjectsTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="company_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Projects $projects
     * @return array
     */
    public function transform(Projects $projects)
    {
        return array_merge($this->getDefaults($projects), [
            
            'id' => (int) $projects->public_id,
            'updated_at' => $this->getTimestamp($projects->updated_at),
            'archived_at' => $this->getTimestamp($projects->deleted_at),
        ]);
    }
}
