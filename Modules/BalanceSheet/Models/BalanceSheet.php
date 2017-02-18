<?php

namespace Modules\BalanceSheet\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class BalanceSheet extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\BalanceSheet\Presenters\BalanceSheetPresenter';

    /**
     * @var string
     */
    protected $fillable = [""];

    /**
     * @var string
     */
    protected $table = 'balancesheet';

    public function getEntityType()
    {
        return 'balancesheet';
    }

}
