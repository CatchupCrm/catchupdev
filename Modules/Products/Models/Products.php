<?php

namespace Modules\Products\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\Products\Presenters\ProductsPresenter';

    /**
     * @var string
     */
    protected $fillable = [""];

    /**
     * @var string
     */
    protected $table = 'products';

    public function getEntityType()
    {
        return 'products';
    }

}
