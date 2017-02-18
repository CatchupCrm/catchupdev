<?php

namespace App\Ninja\Transformers;

use App\Models\CompanyToken;
use League\Fractal\TransformerAbstract;

/**
 * Class CompanyTokenTransformer.
 */
class CompanyTokenTransformer extends TransformerAbstract
{
    /**
     * @param CompanyToken $company_token
     *
     * @return array
     */
    public function transform(CompanyToken $company_token)
    {
        return [
            'name' => $company_token->name,
            'token' => $company_token->token,
        ];
    }
}
