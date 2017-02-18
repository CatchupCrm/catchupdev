<?php

namespace Modules\Email\Repositories;

use DB;
use Modules\Email\Models\Email;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\EmailWasCreated;
//use App\Events\EmailWasUpdated;

class EmailRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Email\Models\Email';
    }

    public function all()
    {
        return Email::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('email')
                    ->where('email.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'email.public_id',
                        'email.deleted_at',
                        'email.created_at',
                        'email.is_deleted',
                        'email.user_id'
                    );

        $this->applyFilters($query, 'email');

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

    public function save($data, $email = null)
    {
        $entity = $email ?: Email::createNew();

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
