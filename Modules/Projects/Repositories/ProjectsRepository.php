<?php

namespace Modules\Projects\Repositories;

use DB;
use Modules\Projects\Models\Projects;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\ProjectsWasCreated;
//use App\Events\ProjectsWasUpdated;

class ProjectsRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Projects\Models\Projects';
    }

    public function all()
    {
        return Projects::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('projects')
                    ->where('projects.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'projects.public_id',
                        'projects.deleted_at',
                        'projects.created_at',
                        'projects.is_deleted',
                        'projects.user_id'
                    );

        $this->applyFilters($query, 'projects');

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

    public function save($data, $projects = null)
    {
        $entity = $projects ?: Projects::createNew();

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
