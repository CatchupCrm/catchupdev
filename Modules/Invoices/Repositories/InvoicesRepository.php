<?php

namespace Modules\Invoices\Repositories;

use DB;
use Modules\Invoices\Models\Invoices;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\InvoicesWasCreated;
//use App\Events\InvoicesWasUpdated;

class InvoicesRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Invoices\Models\Invoices';
    }

    public function all()
    {
        return Invoices::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('invoices')
                    ->where('invoices.company_id', '=', \Auth::user()->company_id)
                    ->select(
                        
                        'invoices.public_id',
                        'invoices.deleted_at',
                        'invoices.created_at',
                        'invoices.is_deleted',
                        'invoices.user_id'
                    );

        $this->applyFilters($query, 'invoices');

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

    public function save($data, $invoices = null)
    {
        $entity = $invoices ?: Invoices::createNew();

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
