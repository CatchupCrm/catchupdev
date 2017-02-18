<?php

namespace Modules\BalanceSheet\Repositories;

use DB;
use Modules\Balancesheet\Models\Balancesheet;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\BalancesheetWasCreated;
//use App\Events\BalancesheetWasUpdated;

class BalancesheetRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Balancesheet\Models\Balancesheet';
    }

    public function all()
    {
        return Balancesheet::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('balancesheet')
                    ->where('balancesheet.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'balancesheet.public_id',
                        'balancesheet.deleted_at',
                        'balancesheet.created_at',
                        'balancesheet.is_deleted',
                        'balancesheet.user_id'
                    );

        $this->applyFilters($query, 'balancesheet');

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

    public function save($data, $balancesheet = null)
    {
        $entity = $balancesheet ?: Balancesheet::createNew();

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
