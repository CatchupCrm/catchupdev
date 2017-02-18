<?php

namespace App\Ninja\Repositories;

use DB;

class CompanyGatewayRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'App\Models\CompanyGateway';
    }

    public function find($companyId)
    {
        $query = DB::table('company_gateways')
                    ->join('gateways', 'gateways.id', '=', 'company_gateways.gateway_id')
                    ->where('company_gateways.company_id', '=', $companyId)
                    ->whereNull('company_gateways.deleted_at');

        return $query->select('company_gateways.id', 'company_gateways.public_id', 'gateways.name', 'company_gateways.deleted_at', 'company_gateways.gateway_id');
    }
}
