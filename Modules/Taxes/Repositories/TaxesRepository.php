<?php

namespace Modules\Taxes\Repositories;

use DB;
use Modules\Taxes\Models\Taxes;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\TaxesWasCreated;
//use App\Events\TaxesWasUpdated;

class TaxesRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Taxes\Models\Taxes';
    }

    public function all()
    {
        return Taxes::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('taxes')
                    ->where('taxes.company_id', '=', \Auth::user()->company_id)
                    ->select(
                        
                        'taxes.public_id',
                        'taxes.deleted_at',
                        'taxes.created_at',
                        'taxes.is_deleted',
                        'taxes.user_id'
                    );

        $this->applyFilters($query, 'taxes');

        if ($userId) {
            $query->where('clients.user_id', '=', $userId);
        }

        /*
        if ($filter) {
            $query->where();
        }
        */

        return $query;
    }

    public function save($data, $taxes = null)
    {
        $entity = $taxes ?: Taxes::createNew();

        $entity->fill($data);
        $entity->save();

        /*
        if (!$publicId || $publicId == '-1') {
            event(new ClientWasCreated($client));
        } else {
            event(new ClientWasUpdated($client));
        }
        */

        return $entity;
    }

}
