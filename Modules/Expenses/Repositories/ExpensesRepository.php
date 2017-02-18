<?php

namespace Modules\Expenses\Repositories;

use DB;
use Modules\Expenses\Models\Expenses;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\ExpensesWasCreated;
//use App\Events\ExpensesWasUpdated;

class ExpensesRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Expenses\Models\Expenses';
    }

    public function all()
    {
        return Expenses::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('expenses')
                    ->where('expenses.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'expenses.public_id',
                        'expenses.deleted_at',
                        'expenses.created_at',
                        'expenses.is_deleted',
                        'expenses.user_id'
                    );

        $this->applyFilters($query, 'expenses');

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

    public function save($data, $expenses = null)
    {
        $entity = $expenses ?: Expenses::createNew();

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
