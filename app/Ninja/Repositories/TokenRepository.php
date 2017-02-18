<?php

namespace App\Ninja\Repositories;

use App\Models\Token;
use DB;

class TokenRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'App\Models\CompanyToken';
    }

    public function find($userId)
    {
        $query = DB::table('company_tokens')
            ->where('company_tokens.user_id', '=', $userId)
            ->whereNull('company_tokens.deleted_at');;

        return $query->select('company_tokens.public_id', 'company_tokens.name', 'company_tokens.token', 'company_tokens.public_id', 'company_tokens.deleted_at');
    }
}
