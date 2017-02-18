<?php

namespace Modules\Core\Repositories;

use DB;
use Modules\Core\Models\Core;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\CoreWasCreated;
//use App\Events\CoreWasUpdated;

class CoreRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Core\Models\Core';
    }

    public function all()
    {
        return Core::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('core')
                    ->where('core.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'core.public_id',
                        'core.deleted_at',
                        'core.created_at',
                        'core.is_deleted',
                        'core.user_id'
                    );

        $this->applyFilters($query, 'core');

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

    public function save($data, $core = null)
    {
        $entity = $core ?: Core::createNew();

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
