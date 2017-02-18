<?php

namespace Modules\Workflows\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workflows extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\Workflows\Presenters\WorkflowsPresenter';

    /**
     * @var string
     */
    protected $fillable = [""];

    /**
     * @var string
     */
    protected $table = 'workflows';

    public function getEntityType()
    {
        return 'workflows';
    }

}
