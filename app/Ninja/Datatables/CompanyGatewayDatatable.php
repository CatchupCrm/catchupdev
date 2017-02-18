<?php

namespace App\Ninja\Datatables;

use App\Models\CompanyGateway;
use App\Models\CompanyGatewaySettings;
use App\Models\GatewayType;
use Cache;
use URL;
use Utils;

class CompanyGatewayDatatable extends EntityDatatable
{
    private static $companyGateways;

    public $entityType = ENTITY_COMPANY_GATEWAY;

    public function columns()
    {
        return [
            [
                'name',
                function ($model) {
                    if ($model->deleted_at) {
                        return $model->name;
                    } elseif ($model->gateway_id == GATEWAY_CUSTOM) {
                        $companyGateway = $this->getCompanyGateway($model->id);
                        $name = $companyGateway->getConfigField('name') . ' [' . trans('texts.custom') . ']';

                        return link_to("gateways/{$model->public_id}/edit", $name)->toHtml();
                    } elseif ($model->gateway_id != GATEWAY_WEPAY) {
                        return link_to("gateways/{$model->public_id}/edit", $model->name)->toHtml();
                    } else {
                        $companyGateway = $this->getCompanyGateway($model->id);
                        $config = $companyGateway->getConfig();
                        $endpoint = WEPAY_ENVIRONMENT == WEPAY_STAGE ? 'https://stage.wepay.com/' : 'https://www.wepay.com/';
                        $wepayCompanyId = $config->companyId;
                        $wepayState = isset($config->state) ? $config->state : null;
                        $linkText = $model->name;
                        $url = $endpoint . 'company/' . $wepayCompanyId;
                        $html = link_to($url, $linkText, ['target' => '_blank'])->toHtml();

                        try {
                            if ($wepayState == 'action_required') {
                                $updateUri = $endpoint . 'api/company_update/' . $wepayCompanyId . '?redirect_uri=' . urlencode(URL::to('gateways'));
                                $linkText .= ' <span style="color:#d9534f">(' . trans('texts.action_required') . ')</span>';
                                $url = $updateUri;
                                $html = "<a href=\"{$url}\">{$linkText}</a>";
                                $model->setupUrl = $url;
                            } elseif ($wepayState == 'pending') {
                                $linkText .= ' (' . trans('texts.resend_confirmation_email') . ')';
                                $model->resendConfirmationUrl = $url = URL::to("gateways/{$companyGateway->public_id}/resend_confirmation");
                                $html = link_to($url, $linkText)->toHtml();
                            }
                        } catch (\WePayException $ex) {
                        }

                        return $html;
                    }
                },
            ],
            [
                'limit',
                function ($model) {
                    if ($model->gateway_id == GATEWAY_CUSTOM) {
                        $gatewayTypes = [GATEWAY_TYPE_CUSTOM];
                    } else {
                        $companyGateway = $this->getCompanyGateway($model->id);
                        $paymentDriver = $companyGateway->paymentDriver();
                        $gatewayTypes = $paymentDriver->gatewayTypes();
                        $gatewayTypes = array_diff($gatewayTypes, [GATEWAY_TYPE_TOKEN]);
                    }

                    $html = '';
                    foreach ($gatewayTypes as $gatewayTypeId) {
                        $companyGatewaySettings = CompanyGatewaySettings::scope()->where('company_gateway_settings.gateway_type_id',
                            '=', $gatewayTypeId)->first();
                        $gatewayType = GatewayType::find($gatewayTypeId);

                        if (count($gatewayTypes) > 1) {
                            if ($html) {
                                $html .= '<br>';
                            }

                            $html .= $gatewayType->name . ' &mdash; ';
                        }

                        if ($companyGatewaySettings && $companyGatewaySettings->min_limit !== null && $companyGatewaySettings->max_limit !== null) {
                            $html .= Utils::formatMoney($companyGatewaySettings->min_limit) . ' - ' . Utils::formatMoney($companyGatewaySettings->max_limit);
                        } elseif ($companyGatewaySettings && $companyGatewaySettings->min_limit !== null) {
                            $html .= trans('texts.min_limit',
                                ['min' => Utils::formatMoney($companyGatewaySettings->min_limit)]
                            );
                        } elseif ($companyGatewaySettings && $companyGatewaySettings->max_limit !== null) {
                            $html .= trans('texts.max_limit',
                                ['max' => Utils::formatMoney($companyGatewaySettings->max_limit)]
                            );
                        } else {
                            $html .= trans('texts.no_limit');
                        }
                    }

                    return $html;
                },
            ],
        ];
    }

    public function actions()
    {
        $actions = [
            [
                uctrans('texts.resend_confirmation_email'),
                function ($model) {
                    return $model->resendConfirmationUrl;
                },
                function ($model) {
                    return !$model->deleted_at && $model->gateway_id == GATEWAY_WEPAY && !empty($model->resendConfirmationUrl);
                },
            ], [
                uctrans('texts.edit_gateway'),
                function ($model) {
                    return URL::to("gateways/{$model->public_id}/edit");
                },
                function ($model) {
                    return !$model->deleted_at;
                },
            ], [
                uctrans('texts.finish_setup'),
                function ($model) {
                    return $model->setupUrl;
                },
                function ($model) {
                    return !$model->deleted_at && $model->gateway_id == GATEWAY_WEPAY && !empty($model->setupUrl);
                },
            ], [
                uctrans('texts.manage_company'),
                function ($model) {
                    $companyGateway = $this->getCompanyGateway($model->id);
                    $endpoint = WEPAY_ENVIRONMENT == WEPAY_STAGE ? 'https://stage.wepay.com/' : 'https://www.wepay.com/';

                    return [
                        'url' => $endpoint . 'company/' . $companyGateway->getConfig()->companyId,
                        'attributes' => 'target="_blank"',
                    ];
                },
                function ($model) {
                    return !$model->deleted_at && $model->gateway_id == GATEWAY_WEPAY;
                },
            ], [
                uctrans('texts.terms_of_service'),
                function ($model) {
                    return 'https://go.wepay.com/terms-of-service-us';
                },
                function ($model) {
                    return $model->gateway_id == GATEWAY_WEPAY;
                },
            ],
        ];

        foreach (Cache::get('gatewayTypes') as $gatewayType) {
            $actions[] = [
                trans('texts.set_limits', ['gateway_type' => $gatewayType->name]),
                function () use ($gatewayType) {
                    $companyGatewaySettings = CompanyGatewaySettings::scope()
                        ->where('company_gateway_settings.gateway_type_id', '=', $gatewayType->id)
                        ->first();
                    $min = $companyGatewaySettings && $companyGatewaySettings->min_limit !== null ? $companyGatewaySettings->min_limit : 'null';
                    $max = $companyGatewaySettings && $companyGatewaySettings->max_limit !== null ? $companyGatewaySettings->max_limit : 'null';

                    return "javascript:showLimitsModal('{$gatewayType->name}', {$gatewayType->id}, $min, $max)";
                },
                function ($model) use ($gatewayType) {
                    // Only show this action if the given gateway supports this gateway type
                    if ($model->gateway_id == GATEWAY_CUSTOM) {
                        return $gatewayType->id == GATEWAY_TYPE_CUSTOM;
                    } else {
                        $companyGateway = $this->getCompanyGateway($model->id);
                        $paymentDriver = $companyGateway->paymentDriver();
                        $gatewayTypes = $paymentDriver->gatewayTypes();

                        return in_array($gatewayType->id, $gatewayTypes);
                    }
                },
            ];
        }

        return $actions;
    }

    private function getCompanyGateway($id)
    {
        if (isset(static::$companyGateways[$id])) {
            return static::$companyGateways[$id];
        }

        static::$companyGateways[$id] = CompanyGateway::find($id);

        return static::$companyGateways[$id];
    }
}
