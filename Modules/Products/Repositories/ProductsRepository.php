<?php

namespace Modules\Products\Repositories;

use DB;
use Modules\Products\Models\Products;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\ProductsWasCreated;
//use App\Events\ProductsWasUpdated;

class ProductsRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Products\Models\Products';
    }

    public function all()
    {
        return Products::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('products')
                    ->where('products.company_id', '=', \Auth::user()->company_id)
                    ->select(
                        
                        'products.public_id',
                        'products.deleted_at',
                        'products.created_at',
                        'products.is_deleted',
                        'products.user_id'
                    );

        $this->applyFilters($query, 'products');

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

    public function save($data, $products = null)
    {
        $entity = $products ?: Products::createNew();

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
