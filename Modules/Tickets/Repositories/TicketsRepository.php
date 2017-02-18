<?php

namespace Modules\Tickets\Repositories;

use DB;
use Modules\Tickets\Models\Tickets;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\TicketsWasCreated;
//use App\Events\TicketsWasUpdated;

class TicketsRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Tickets\Models\Tickets';
    }

    public function all()
    {
        return Tickets::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('tickets')
                    ->where('tickets.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'tickets.public_id',
                        'tickets.deleted_at',
                        'tickets.created_at',
                        'tickets.is_deleted',
                        'tickets.user_id'
                    );

        $this->applyFilters($query, 'tickets');

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

    public function save($data, $tickets = null)
    {
        $entity = $tickets ?: Tickets::createNew();

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
