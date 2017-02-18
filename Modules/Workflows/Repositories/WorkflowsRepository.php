<?php

namespace Modules\Workflows\Repositories;

use DB;
use Modules\Workflows\Models\Workflows;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\WorkflowsWasCreated;
//use App\Events\WorkflowsWasUpdated;

class WorkflowsRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Workflows\Models\Workflows';
    }

    public function all()
    {
        return Workflows::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('workflows')
                    ->where('workflows.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'workflows.public_id',
                        'workflows.deleted_at',
                        'workflows.created_at',
                        'workflows.is_deleted',
                        'workflows.user_id'
                    );

        $this->applyFilters($query, 'workflows');

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

    public function save($data, $workflows = null)
    {
        $entity = $workflows ?: Workflows::createNew();

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
