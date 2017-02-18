<?php

namespace Modules\Banking\Repositories;

use DB;
use Modules\Banking\Models\Banking;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\BankingWasCreated;
//use App\Events\BankingWasUpdated;

class BankingRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Banking\Models\Banking';
    }

    public function all()
    {
        return Banking::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('banking')
                    ->where('banking.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'banking.public_id',
                        'banking.deleted_at',
                        'banking.created_at',
                        'banking.is_deleted',
                        'banking.user_id'
                    );

        $this->applyFilters($query, 'banking');

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

    public function save($data, $banking = null)
    {
        $entity = $banking ?: Banking::createNew();

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
