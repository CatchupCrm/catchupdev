<?php

namespace Modules\Leads\Repositories;

use DB;
use Modules\Leads\Models\Leads;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\LeadsWasCreated;
//use App\Events\LeadsWasUpdated;

class LeadsRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Leads\Models\Leads';
    }

    public function all()
    {
        return Leads::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('leads')
                    ->where('leads.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'leads.public_id',
                        'leads.deleted_at',
                        'leads.created_at',
                        'leads.is_deleted',
                        'leads.user_id'
                    );

        $this->applyFilters($query, 'leads');

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

    public function save($data, $leads = null)
    {
        $entity = $leads ?: Leads::createNew();

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
