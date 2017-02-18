<?php

namespace Modules\Banking\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banking extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\Banking\Presenters\BankingPresenter';

    /**
     * @var string
     */
    protected $fillable = [""];

    /**
     * @var string
     */
    protected $table = 'banking';

    public function getEntityType()
    {
        return 'banking';
    }

}
