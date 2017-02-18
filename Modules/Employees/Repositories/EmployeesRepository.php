<?php

namespace Modules\Employees\Repositories;

use DB;
use Modules\Employees\Models\Employees;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\EmployeesWasCreated;
//use App\Events\EmployeesWasUpdated;

class EmployeesRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Employees\Models\Employees';
    }

    public function all()
    {
        return Employees::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('employees')
                    ->where('employees.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'employees.public_id',
                        'employees.deleted_at',
                        'employees.created_at',
                        'employees.is_deleted',
                        'employees.user_id'
                    );

        $this->applyFilters($query, 'employees');

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

    public function save($data, $employees = null)
    {
        $entity = $employees ?: Employees::createNew();

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
