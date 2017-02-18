<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyGateway;
use App\Models\Gateway;
use App\Services\CompanyGatewayService;
use Auth;
use Input;
use Redirect;
use Session;
use stdClass;
use URL;
use Utils;
use Validator;
use View;
use WePay;

class CompanyGatewayController extends BaseController
{
    protected $companyGatewayService;

    public function __construct(CompanyGatewayService $companyGatewayService)
    {
        //parent::__construct();

        $this->companyGatewayService = $companyGatewayService;
    }

    public function index()
    {
        return Redirect::to('settings/' . COMPANY_PAYMENTS);
    }

    public function getDatatable()
    {
        return $this->companyGatewayService->getDatatable(Auth::user()->company_id);
    }

    public function show($publicId)
    {
        Session::reflash();

        return Redirect::to("gateways/$publicId/edit");
    }

    public function edit($publicId)
    {
        $companyGateway = CompanyGateway::scope($publicId)->firstOrFail();
        $config = $companyGateway->getConfig();

        if ($companyGateway->gateway_id != GATEWAY_CUSTOM) {
            foreach ($config as $field => $value) {
                $config->$field = str_repeat('*', strlen($value));
            }
        }

        $data = self::getViewModel($companyGateway);
        $data['url'] = 'gateways/' . $publicId;
        $data['method'] = 'PUT';
        $data['title'] = trans('texts.edit_gateway') . ' - ' . $companyGateway->gateway->name;
        $data['config'] = $config;
        $data['hiddenFields'] = Gateway::$hiddenFields;
        $data['selectGateways'] = Gateway::where('id', '=', $companyGateway->gateway_id)->get();

        return View::make('companies.company_gateway', $data);
    }

    public function update($publicId)
    {
        return $this->save($publicId);
    }

    public function store()
    {
        return $this->save();
    }

    /**
     * Displays the form for company creation.
     */
    public function create()
    {
        if (!\Request::secure() && !Utils::isNinjaDev()) {
            Session::flash('warning', trans('texts.enable_https'));
        }

        $company = Auth::user()->company;
        $companyGatewaysIds = $company->gatewayIds();
        $otherProviders = Input::get('other_providers');

        if (!Utils::isNinja() || !env('WEPAY_CLIENT_ID') || Gateway::hasStandardGateway($companyGatewaysIds)) {
            $otherProviders = true;
        }

        $data = self::getViewModel();
        $data['url'] = 'gateways';
        $data['method'] = 'POST';
        $data['title'] = trans('texts.add_gateway');

        if ($otherProviders) {
            $availableGatewaysIds = $company->availableGatewaysIds();
            $data['primaryGateways'] = Gateway::primary($availableGatewaysIds)->orderBy('sort_order')->get();
            $data['secondaryGateways'] = Gateway::secondary($availableGatewaysIds)->orderBy('name')->get();
            $data['hiddenFields'] = Gateway::$hiddenFields;

            return View::make('companies.company_gateway', $data);
        } else {
            return View::make('companies.company_gateway_wepay', $data);
        }
    }

    private function getViewModel($companyGateway = false)
    {
        $selectedCards = $companyGateway ? $companyGateway->accepted_credit_cards : 0;
        $user = Auth::user();
        $company = $user->company;

        $creditCardsArray = unserialize(CREDIT_CARDS);
        $creditCards = [];
        foreach ($creditCardsArray as $card => $name) {
            if ($selectedCards > 0 && ($selectedCards & $card) == $card) {
                $creditCards[$name['text']] = ['value' => $card, 'data-imageUrl' => asset($name['card']), 'checked' => 'checked'];
            } else {
                $creditCards[$name['text']] = ['value' => $card, 'data-imageUrl' => asset($name['card'])];
            }
        }

        $company->load('company_gateways');
        $currentGateways = $company->company_gateways;
        $gateways = Gateway::where('payment_library_id', '=', 1)->orderBy('name')->get();

        foreach ($gateways as $gateway) {
            $fields = $gateway->getFields();
            if (!$gateway->isCustom()) {
                asort($fields);
            }
            $gateway->fields = $gateway->id == GATEWAY_WEPAY ? [] : $fields;
            if ($companyGateway && $companyGateway->gateway_id == $gateway->id) {
                $companyGateway->fields = $gateway->fields;
            }
        }

        return [
            'company' => $company,
            'user' => $user,
            'companyGateway' => $companyGateway,
            'config' => false,
            'gateways' => $gateways,
            'creditCardTypes' => $creditCards,
            'countGateways' => count($currentGateways),
        ];
    }

    public function bulk()
    {
        $action = Input::get('bulk_action');
        $ids = Input::get('bulk_public_id');
        $count = $this->companyGatewayService->bulk($ids, $action);

        Session::flash('message', trans("texts.{$action}d_company_gateway"));

        return Redirect::to('settings/' . COMPANY_PAYMENTS);
    }

    /**
     * Stores new company.
     *
     * @param mixed $companyGatewayPublicId
     */
    public function save($companyGatewayPublicId = false)
    {
        $gatewayId = Input::get('primary_gateway_id') ?: Input::get('secondary_gateway_id');
        $gateway = Gateway::findOrFail($gatewayId);

        $rules = [];
        $fields = $gateway->getFields();
        $optional = array_merge(Gateway::$hiddenFields, Gateway::$optionalFields);

        if ($gatewayId == GATEWAY_DWOLLA) {
            $optional = array_merge($optional, ['key', 'secret']);
        } elseif ($gatewayId == GATEWAY_STRIPE) {
            if (Utils::isNinjaDev()) {
                // do nothing - we're unable to acceptance test with StripeJS
            } else {
                $rules['publishable_key'] = 'required';
                $rules['enable_ach'] = 'boolean';
            }
        }

        if ($gatewayId != GATEWAY_WEPAY) {
            foreach ($fields as $field => $details) {
                if (!in_array($field, $optional)) {
                    if (strtolower($gateway->name) == 'beanstream') {
                        if (in_array($field, ['merchant_id', 'passCode'])) {
                            $rules[$gateway->id . '_' . $field] = 'required';
                        }
                    } else {
                        $rules[$gateway->id . '_' . $field] = 'required';
                    }
                }
            }
        }

        $creditcards = Input::get('creditCardTypes');
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('gateways/create?other_providers=' . ($gatewayId == GATEWAY_WEPAY ? 'false' : 'true'))
                ->withErrors($validator)
                ->withInput();
        } else {
            $company = Company::with('company_gateways')->findOrFail(Auth::user()->company_id);
            $oldConfig = null;

            if ($companyGatewayPublicId) {
                $companyGateway = CompanyGateway::scope($companyGatewayPublicId)->firstOrFail();
                $oldConfig = $companyGateway->getConfig();
            } else {
                // check they don't already have an active gateway for this provider
                // TODO complete this
                $companyGateway = CompanyGateway::scope()
                    ->whereGatewayId($gatewayId)
                    ->first();
                if ($companyGateway) {
                    Session::flash('error', trans('texts.gateway_exists'));

                    return Redirect::to("gateways/{$companyGateway->public_id}/edit");
                }

                $companyGateway = CompanyGateway::createNew();
                $companyGateway->gateway_id = $gatewayId;

                if ($gatewayId == GATEWAY_WEPAY) {
                    if (!$this->setupWePay($companyGateway, $wepayResponse)) {
                        return $wepayResponse;
                    }
                    $oldConfig = $companyGateway->getConfig();
                }
            }

            $config = new stdClass();

            if ($gatewayId != GATEWAY_WEPAY) {
                foreach ($fields as $field => $details) {
                    $value = trim(Input::get($gateway->id . '_' . $field));
                    // if the new value is masked use the original value
                    if ($oldConfig && $value && $value === str_repeat('*', strlen($value))) {
                        $value = $oldConfig->$field;
                    }
                    if (!$value && ($field == 'testMode' || $field == 'developerMode')) {
                        // do nothing
                    } elseif ($gatewayId == GATEWAY_CUSTOM) {
                        $config->$field = strip_tags($value);
                    } else {
                        $config->$field = $value;
                    }
                }
            } elseif ($oldConfig) {
                $config = clone $oldConfig;
            }

            $publishableKey = trim(Input::get('publishable_key'));
            if ($publishableKey = str_replace('*', '', $publishableKey)) {
                $config->publishableKey = $publishableKey;
            } elseif ($oldConfig && property_exists($oldConfig, 'publishableKey')) {
                $config->publishableKey = $oldConfig->publishableKey;
            }

            $plaidClientId = trim(Input::get('plaid_client_id'));
            if ($plaidClientId = str_replace('*', '', $plaidClientId)) {
                $config->plaidClientId = $plaidClientId;
            } elseif ($oldConfig && property_exists($oldConfig, 'plaidClientId')) {
                $config->plaidClientId = $oldConfig->plaidClientId;
            }

            $plaidSecret = trim(Input::get('plaid_secret'));
            if ($plaidSecret = str_replace('*', '', $plaidSecret)) {
                $config->plaidSecret = $plaidSecret;
            } elseif ($oldConfig && property_exists($oldConfig, 'plaidSecret')) {
                $config->plaidSecret = $oldConfig->plaidSecret;
            }

            $plaidPublicKey = trim(Input::get('plaid_public_key'));
            if ($plaidPublicKey = str_replace('*', '', $plaidPublicKey)) {
                $config->plaidPublicKey = $plaidPublicKey;
            } elseif ($oldConfig && property_exists($oldConfig, 'plaidPublicKey')) {
                $config->plaidPublicKey = $oldConfig->plaidPublicKey;
            }

            if ($gatewayId == GATEWAY_STRIPE || $gatewayId == GATEWAY_WEPAY) {
                $config->enableAch = boolval(Input::get('enable_ach'));
            }

            if ($gatewayId == GATEWAY_BRAINTREE) {
                $config->enablePayPal = boolval(Input::get('enable_paypal'));
            }

            $cardCount = 0;
            if ($creditcards) {
                foreach ($creditcards as $card => $value) {
                    $cardCount += intval($value);
                }
            }

            $companyGateway->accepted_credit_cards = $cardCount;
            $companyGateway->show_address = Input::get('show_address') ? true : false;
            $companyGateway->update_address = Input::get('update_address') ? true : false;
            $companyGateway->setConfig($config);

            if ($companyGatewayPublicId) {
                $companyGateway->save();
            } else {
                $company->company_gateways()->save($companyGateway);
            }

            if (isset($wepayResponse)) {
                return $wepayResponse;
            } else {
                $this->testGateway($companyGateway);

                if ($companyGatewayPublicId) {
                    $message = trans('texts.updated_gateway');
                    Session::flash('message', $message);

                    return Redirect::to("gateways/{$companyGateway->public_id}/edit");
                } else {
                    $message = trans('texts.created_gateway');
                    Session::flash('message', $message);

                    return Redirect::to('/settings/online_payments');
                }
            }
        }
    }

    private function testGateway($companyGateway)
    {
        $paymentDriver = $companyGateway->paymentDriver();
        $result = $paymentDriver->isValid();

        if ($result !== true) {
            Session::flash('error', $result . ' - ' . trans('texts.gateway_config_error'));
        }
    }

    protected function getWePayUpdateUri($companyGateway)
    {
        if ($companyGateway->gateway_id != GATEWAY_WEPAY) {
            return null;
        }

        $wepay = Utils::setupWePay($companyGateway);

        $update_uri_data = $wepay->request('company/get_update_uri', [
            'company_id' => $companyGateway->getConfig()->companyId,
            'mode' => 'iframe',
            'redirect_uri' => URL::to('/gateways'),
        ]);

        return $update_uri_data->uri;
    }

    protected function setupWePay($companyGateway, &$response)
    {
        $user = Auth::user();
        $company = $user->company;

        $rules = [
            'company_name' => 'required',
            'tos_agree' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
        ];

        if (WEPAY_ENABLE_CANADA) {
            $rules['country'] = 'required|in:US,CA';
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('gateways/create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $wepay = Utils::setupWePay();

            $userDetails = [
                'client_id' => WEPAY_CLIENT_ID,
                'client_secret' => WEPAY_CLIENT_SECRET,
                'email' => Input::get('email'),
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'original_ip' => \Request::getClientIp(true),
                'original_device' => \Request::server('HTTP_USER_AGENT'),
                'tos_acceptance_time' => time(),
                'redirect_uri' => URL::to('gateways'),
                'scope' => 'manage_companies,collect_payments,view_user,preapprove_payments,send_money',
            ];

            $wepayUser = $wepay->request('user/register/', $userDetails);

            $accessToken = $wepayUser->access_token;
            $accessTokenExpires = $wepayUser->expires_in ? (time() + $wepayUser->expires_in) : null;

            $wepay = new WePay($accessToken);

            $companyDetails = [
                'name' => Input::get('company_name'),
                'description' => trans('texts.wepay_company_description'),
                'theme_object' => json_decode(WEPAY_THEME),
                'callback_uri' => $companyGateway->getWebhookUrl(),
                'rbits' => $company->present()->rBits,
            ];

            if (WEPAY_ENABLE_CANADA) {
                $companyDetails['country'] = Input::get('country');

                if (Input::get('country') == 'CA') {
                    $companyDetails['currencies'] = ['CAD'];
                    $companyDetails['country_options'] = ['debit_opt_in' => boolval(Input::get('debit_cards'))];
                }
            }

            $wepayCompany = $wepay->request('company/create/', $companyDetails);

            try {
                $wepay->request('user/send_confirmation/', []);
                $confirmationRequired = true;
            } catch (\WePayException $ex) {
                if ($ex->getMessage() == 'This access_token is already approved.') {
                    $confirmationRequired = false;
                } else {
                    throw $ex;
                }
            }

            $companyGateway->gateway_id = GATEWAY_WEPAY;
            $companyGateway->setConfig([
                'userId' => $wepayUser->user_id,
                'accessToken' => $accessToken,
                'tokenType' => $wepayUser->token_type,
                'tokenExpires' => $accessTokenExpires,
                'companyId' => $wepayCompany->company_id,
                'state' => $wepayCompany->state,
                'testMode' => WEPAY_ENVIRONMENT == WEPAY_STAGE,
                'country' => WEPAY_ENABLE_CANADA ? Input::get('country') : 'US',
            ]);

            if ($confirmationRequired) {
                Session::flash('message', trans('texts.created_wepay_confirmation_required'));
            } else {
                $updateUri = $wepay->request('/company/get_update_uri', [
                    'company_id' => $wepayCompany->company_id,
                    'redirect_uri' => URL::to('gateways'),
                ]);

                $response = Redirect::to($updateUri->uri);

                return true;
            }

            $response = Redirect::to("gateways/{$companyGateway->public_id}/edit");

            return true;
        } catch (\WePayException $e) {
            Session::flash('error', $e->getMessage());
            $response = Redirect::to('gateways/create')
                ->withInput();

            return false;
        }
    }

    public function resendConfirmation($publicId = false)
    {
        $companyGateway = CompanyGateway::scope($publicId)->firstOrFail();

        if ($companyGateway->gateway_id == GATEWAY_WEPAY) {
            try {
                $wepay = Utils::setupWePay($companyGateway);
                $wepay->request('user/send_confirmation', []);

                Session::flash('message', trans('texts.resent_confirmation_email'));
            } catch (\WePayException $e) {
                Session::flash('error', $e->getMessage());
            }
        }

        return Redirect::to("gateways/{$companyGateway->public_id}/edit");
    }
}
