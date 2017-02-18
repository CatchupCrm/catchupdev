<?php

namespace Modules\Relations\Repositories;

use DB;
use Modules\Relations\Models\Relations;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\RelationsWasCreated;
//use App\Events\RelationsWasUpdated;

class RelationsRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Relations\Models\Relations';
    }

    public function all()
    {
        return Relations::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('relations')
                    ->where('relations.company_id', '=', \Auth::user()->company_id)
                    ->select(
                        
                        'relations.public_id',
                        'relations.deleted_at',
                        'relations.created_at',
                        'relations.is_deleted',
                        'relations.user_id'
                    );

        $this->applyFilters($query, 'relations');

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

    public function save($data, $relations = null)
    {
        $entity = $relations ?: Relations::createNew();

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
