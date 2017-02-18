<?php

namespace App\Ninja\Transformers;

use App\Models\Company;
use App\Models\User;

class UserCompanyTransformer extends EntityTransformer
{
    protected $defaultIncludes = [
        'user',
    ];

    protected $tokenName;

    public function __construct(Company $company, $serializer, $tokenName)
    {
        parent::__construct($company, $serializer);

        $this->tokenName = $tokenName;
    }

    public function includeUser(User $user)
    {
        $transformer = new UserTransformer($this->company, $this->serializer);

        return $this->includeItem($user, $transformer, 'user');
    }

    public function transform(User $user)
    {
        return [
            'company_key' => $user->company->company_key,
            'name' => $user->company->present()->name,
            'token' => $user->company->getToken($user->id, $this->tokenName),
            'default_url' => SITE_URL,
            'plan' => $user->company->corporation->plan,
            'logo' => $user->company->logo,
            'logo_url' => $user->company->getLogoURL(),
        ];
    }
}
