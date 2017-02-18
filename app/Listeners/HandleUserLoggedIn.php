<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Events\UserSignedUp;
use App\Libraries\HistoryUtils;
use App\Ninja\Repositories\CompanyRepository;
use Auth;
use Carbon;
use Session;

/**
 * Class HandleUserLoggedIn.
 */
class HandleUserLoggedIn
{
    /**
     * @var CompanyRepository
     */
    protected $companyRepo;

    /**
     * Create the event handler.
     *
     * @param CompanyRepository $companyRepo
     */
    public function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepo = $companyRepo;
    }

    /**
     * Handle the event.
     *
     * @param UserLoggedIn $event
     *
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        $company = Auth::user()->company;

        if (empty($company->last_login)) {
            event(new UserSignedUp());
        }

        $company->last_login = Carbon::now()->toDateTimeString();
        $company->save();

        $users = $this->companyRepo->loadCompanys(Auth::user()->id);
        Session::put(SESSION_USER_ACCOUNTS, $users);
        HistoryUtils::loadHistory($users ?: Auth::user()->id);

        $company->loadLocalizationSettings();

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
            Session::flash('warning', trans('texts.iphone_app_message', ['link' => link_to(NINJA_IOS_APP_URL, trans('texts.iphone_app'))]));
        }

        // if they're using Stripe make sure they're using Stripe.js
        $companyGateway = $company->getGatewayConfig(GATEWAY_STRIPE);
        if ($companyGateway && !$companyGateway->getPublishableStripeKey()) {
            Session::flash('warning', trans('texts.missing_publishable_key'));
        } elseif ($company->isLogoTooLarge()) {
            Session::flash('warning', trans('texts.logo_too_large', ['size' => $company->getLogoSize() . 'KB']));
        }
    }
}
