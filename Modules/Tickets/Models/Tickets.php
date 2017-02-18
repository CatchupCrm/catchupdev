<?php

namespace Modules\Tickets\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tickets extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\Tickets\Presenters\TicketsPresenter';

    /**
     * @var string
     */
    protected $fillable = [""];

    /**
     * @var string
     */
    protected $table = 'tickets';

    public function getEntityType()
    {
        return 'tickets';
    }

}
