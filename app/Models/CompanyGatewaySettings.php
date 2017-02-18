<?php

namespace App\Models;

/**
 * Class CompanyGatewaySettings.
 */
class CompanyGatewaySettings extends EntityModel
{
    /**
     * @var array
     */
    protected $dates = ['updated_at'];

    /**
     * @var bool
     */
    protected static $hasPublicId = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gatewayType()
    {
        return $this->belongsTo('App\Models\GatewayType');
    }

    public function setCreatedAtAttribute($value)
    {
        // to Disable created_at
    }
}
