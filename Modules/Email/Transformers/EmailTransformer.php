<?php

namespace Modules\Email\Transformers;

use Modules\Email\Models\Email;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Email", @SWG\Xml(name="Email"))
 */

class EmailTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="company_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="timestamp", example="")
    * @SWG\Property(property="archived_at", type="timestamp", example="1451160233")
    */

    /**
     * @param Email $email
     * @return array
     */
    public function transform(Email $email)
    {
        return array_merge($this->getDefaults($email), [
            
            'id' => (int) $email->public_id,
            'updated_at' => $this->getTimestamp($email->updated_at),
            'archived_at' => $this->getTimestamp($email->deleted_at),
        ]);
    }
}
