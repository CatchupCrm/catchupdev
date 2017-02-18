<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOnlinePaymentRequest;
use App\Models\Company;
use App\Models\Client;
use App\Models\GatewayType;
use App\Models\Invitation;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Ninja\Mailers\UserMailer;
use App\Ninja\Repositories\ClientRepository;
use App\Ninja\Repositories\InvoiceRepository;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use Auth;
use Crawler;
use Exception;
use Input;
use Session;
use URL;
use Utils;
use Validator;
use View;

/**
 * Class OnlinePaymentController.
 */
class OnlinePaymentController extends BaseController
{
    /**
     * @var PaymentService
     */
    protected $paymentService;

    /**
     * @var UserMailer
     */
    protected $userMailer;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepo;

    /**
     * OnlinePaymentController constructor.
     *
     * @param PaymentService $paymentService
     * @param UserMailer     $userMailer
     */
    public function __construct(PaymentService $paymentService, UserMailer $userMailer, InvoiceRepository $invoiceRepo)
    {
        $this->paymentService = $paymentService;
        $this->userMailer = $userMailer;
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * @param $invitationKey
     * @param bool  $gatewayType
     * @param bool  $sourceId
     * @param mixed $gatewayTypeAlias
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showPayment($invitationKey, $gatewayTypeAlias = false, $sourceId = false)
    {
        if (! $invitation = $this->invoiceRepo->findInvoiceByInvitation($invitationKey)) {
            return response()->view('error', [
                'error' => trans('texts.invoice_not_found'),
                'hideHeader' => true,
            ]);
        }

        if (! $invitation->invoice->canBePaid()) {
            return redirect()->to('view/' . $invitation->invitation_key);
        }

        $invitation = $invitation->load('invoice.client.company.company_gateways.gateway');
        $company = $invitation->company;

        if ($company->requiresAuthorization($invitation->invoice) && ! session('authorized:' . $invitation->invitation_key)) {
            return redirect()->to('view/' . $invitation->invitation_key);
        }

        $company->loadLocalizationSettings($invitation->invoice->client);

        if (! $gatewayTypeAlias) {
            $gatewayTypeId = Session::get($invitation->id . 'gateway_type');
        } elseif ($gatewayTypeAlias != GATEWAY_TYPE_TOKEN) {
            $gatewayTypeId = GatewayType::getIdFromAlias($gatewayTypeAlias);
        } else {
            $gatewayTypeId = $gatewayTypeAlias;
        }

        $paymentDriver = $company->paymentDriver($invitation, $gatewayTypeId);

        try {
            return $paymentDriver->startPurchase(Input::all(), $sourceId);
        } catch (Exception $exception) {
            return $this->error($paymentDriver, $exception);
        }
    }

    /**
     * @param CreateOnlinePaymentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doPayment(CreateOnlinePaymentRequest $request)
    {
        $invitation = $request->invitation;
        $gatewayTypeId = Session::get($invitation->id . 'gateway_type');
        $paymentDriver = $invitation->company->paymentDriver($invitation, $gatewayTypeId);

        if (! $invitation->invoice->canBePaid()) {
            return redirect()->to('view/' . $invitation->invitation_key);
        }

        try {
            $paymentDriver->completeOnsitePurchase($request->all());

            if ($paymentDriver->isTwoStep()) {
                Session::flash('warning', trans('texts.bank_company_verification_next_steps'));
            } else {
                Session::flash('message', trans('texts.applied_payment'));
            }

            return $this->completePurchase($invitation);
        } catch (Exception $exception) {
            return $this->error($paymentDriver, $exception, true);
        }
    }

    /**
     * @param bool  $invitationKey
     * @param mixed $gatewayTypeAlias
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function offsitePayment($invitationKey = false, $gatewayTypeAlias = false)
    {
        $invitationKey = $invitationKey ?: Session::get('invitation_key');
        $invitation = Invitation::with('invoice.invoice_items', 'invoice.client.currency', 'invoice.client.company.company_gateways.gateway')
                        ->where('invitation_key', '=', $invitationKey)->firstOrFail();

        if (! $gatewayTypeAlias) {
            $gatewayTypeId = Session::get($invitation->id . 'gateway_type');
        } elseif ($gatewayTypeAlias != GATEWAY_TYPE_TOKEN) {
            $gatewayTypeId = GatewayType::getIdFromAlias($gatewayTypeAlias);
        } else {
            $gatewayTypeId = $gatewayTypeAlias;
        }

        $paymentDriver = $invitation->company->paymentDriver($invitation, $gatewayTypeId);

        if ($error = Input::get('error_description') ?: Input::get('error')) {
            return $this->error($paymentDriver, $error);
        }

        try {
            if ($paymentDriver->completeOffsitePurchase(Input::all())) {
                Session::flash('message', trans('texts.applied_payment'));
            }

            return $this->completePurchase($invitation, true);
        } catch (Exception $exception) {
            return $this->error($paymentDriver, $exception);
        }
    }

    private function completePurchase($invitation, $isOffsite = false)
    {
        if ($redirectUrl = session('redirect_url:' . $invitation->invitation_key)) {
            $separator = strpos($redirectUrl, '?') === false ? '?' : '&';

            return redirect()->to($redirectUrl . $separator . 'invoice_id=' . $invitation->invoice->public_id);
        } else {
            // Allow redirecting to iFrame for offsite payments
            if ($isOffsite) {
                return redirect()->to($invitation->getLink());
            } else {
                return redirect()->to('view/' . $invitation->invitation_key);
            }
        }
    }

    /**
     * @param $paymentDriver
     * @param $exception
     * @param bool $showPayment
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function error($paymentDriver, $exception, $showPayment = false)
    {
        if (is_string($exception)) {
            $displayError = $exception;
            $logError = $exception;
        } else {
            $displayError = $exception->getMessage();
            $logError = Utils::getErrorString($exception);
        }

        $message = sprintf('%s: %s', ucwords($paymentDriver->providerName()), $displayError);
        Session::flash('error', $message);

        $message = sprintf('Payment Error [%s]: %s', $paymentDriver->providerName(), $logError);
        Utils::logError($message, 'PHP', true);

        $route = $showPayment ? 'payment/' : 'view/';

        return redirect()->to($route . $paymentDriver->invitation->invitation_key);
    }

    /**
     * @param $routingNumber
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankInfo($routingNumber)
    {
        if (strlen($routingNumber) != 9 || ! preg_match('/\d{9}/', $routingNumber)) {
            return response()->json([
                'message' => 'Invalid routing number',
            ], 400);
        }

        $data = PaymentMethod::lookupBankData($routingNumber);

        if (is_string($data)) {
            return response()->json([
                'message' => $data,
            ], 500);
        } elseif (! empty($data)) {
            return response()->json($data);
        }

        return response()->json([
            'message' => 'Bank not found',
        ], 404);
    }

    /**
     * @param $companyKey
     * @param $gatewayId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handlePaymentWebhook($companyKey, $gatewayId)
    {
        $gatewayId = intval($gatewayId);

        $company = Company::where('companies.company_key', '=', $companyKey)->first();

        if (! $company) {
            return response()->json([
                'message' => 'Unknown company',
            ], 404);
        }

        $companyGateway = $company->getGatewayConfig(intval($gatewayId));

        if (! $companyGateway) {
            return response()->json([
                'message' => 'Unknown gateway',
            ], 404);
        }

        $paymentDriver = $companyGateway->paymentDriver();

        try {
            $result = $paymentDriver->handleWebHook(Input::all());

            return response()->json(['message' => $result]);
        } catch (Exception $exception) {
            Utils::logError($exception->getMessage(), 'PHP');

            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function handleBuyNow(ClientRepository $clientRepo, InvoiceService $invoiceService, $gatewayTypeAlias = false)
    {
        if (Crawler::isCrawler()) {
            return redirect()->to(NINJA_WEB_URL, 301);
        }

        $company = Company::whereCompanyKey(Input::get('company_key'))->first();
        $redirectUrl = Input::get('redirect_url');
        $failureUrl = URL::previous();

        if (! $company || ! $company->enable_buy_now_buttons || ! $company->hasFeature(FEATURE_BUY_NOW_BUTTONS)) {
            return redirect()->to("{$failureUrl}/?error=invalid company");
        }

        Auth::onceUsingId($company->users[0]->id);
        $product = Product::scope(Input::get('product_id'))->first();

        if (! $product) {
            return redirect()->to("{$failureUrl}/?error=invalid product");
        }

        // check for existing client using contact_key
        $client = false;
        if ($contactKey = Input::get('contact_key')) {
            $client = Client::scope()->whereHas('contacts', function ($query) use ($contactKey) {
                $query->where('contact_key', $contactKey);
            })->first();
        }
        if (! $client) {
            $rules = [
                'first_name' => 'string|max:100',
                'last_name' => 'string|max:100',
                'email' => 'email|string|max:100',
            ];

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                return redirect()->to("{$failureUrl}/?error=" . $validator->errors()->first());
            }

            $data = [
                'currency_id' => $company->currency_id,
                'contact' => Input::all(),
            ];
            $client = $clientRepo->save($data);
        }

        $data = [
            'client_id' => $client->id,
            'is_public' => true,
            'is_recurring' => filter_var(Input::get('is_recurring'), FILTER_VALIDATE_BOOLEAN),
            'frequency_id' => Input::get('frequency_id'),
            'auto_bill_id' => Input::get('auto_bill_id'),
            'start_date' => Input::get('start_date', date('Y-m-d')),
            'tax_rate1' => $company->default_tax_rate ? $company->default_tax_rate->rate : 0,
            'tax_name1' => $company->default_tax_rate ? $company->default_tax_rate->name : '',
            'invoice_items' => [[
                'product_key' => $product->product_key,
                'notes' => $product->notes,
                'cost' => $product->cost,
                'qty' => 1,
                'tax_rate1' => $product->default_tax_rate ? $product->default_tax_rate->rate : 0,
                'tax_name1' => $product->default_tax_rate ? $product->default_tax_rate->name : '',
            ]],
        ];
        $invoice = $invoiceService->save($data);
        if ($invoice->is_recurring) {
            $invoice = $this->invoiceRepo->createRecurringInvoice($invoice->fresh());
        }
        $invitation = $invoice->invitations[0];
        $link = $invitation->getLink();

        if ($redirectUrl) {
            session(['redirect_url:' . $invitation->invitation_key => $redirectUrl]);
        }

        if ($gatewayTypeAlias) {
            return redirect()->to($invitation->getLink('payment') . "/{$gatewayTypeAlias}");
        } else {
            return redirect()->to($invitation->getLink());
        }
    }
}
