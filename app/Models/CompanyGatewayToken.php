<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CompanyGatewayToken.
 */
class CompanyGatewayToken extends Eloquent
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_methods()
    {
        return $this->hasMany('App\Models\PaymentMethod');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company_gateway()
    {
        return $this->belongsTo('App\Models\CompanyGateway');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function default_payment_method()
    {
        return $this->hasOne('App\Models\PaymentMethod', 'id', 'default_payment_method_id');
    }

    /**
     * @return mixed
     */
    public function autoBillLater()
    {
        if ($this->default_payment_method) {
            return $this->default_payment_method->requiresDelayedAutoBill();
        }

        return false;
    }

    /**
     * @param $query
     * @param $clientId
     * @param $companyGatewayId
     *
     * @return mixed
     */
    public function scopeClientAndGateway($query, $clientId, $companyGatewayId)
    {
        $query->where('client_id', '=', $clientId)
            ->where('company_gateway_id', '=', $companyGatewayId);

        return $query;
    }

    /**
     * @return mixed
     */
    public function gatewayName()
    {
        return $this->company_gateway->gateway->name;
    }

    /**
     * @return bool|string
     */
    public function gatewayLink()
    {
        $companyGateway = $this->company_gateway;

        if ($companyGateway->gateway_id == GATEWAY_STRIPE) {
            return "https://dashboard.stripe.com/customers/{$this->token}";
        } elseif ($companyGateway->gateway_id == GATEWAY_BRAINTREE) {
            $merchantId = $companyGateway->getConfigField('merchantId');
            $testMode = $companyGateway->getConfigField('testMode');

            return $testMode ? "https://sandbox.braintreegateway.com/merchants/{$merchantId}/customers/{$this->token}" : "https://www.braintreegateway.com/merchants/{$merchantId}/customers/{$this->token}";
        } else {
            return false;
        }
    }
}
