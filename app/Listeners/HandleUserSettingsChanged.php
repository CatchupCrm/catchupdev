<?php

namespace App\Listeners;

use App\Events\UserSettingsChanged;
use App\Ninja\Mailers\UserMailer;
use App\Ninja\Repositories\CompanyRepository;
use Auth;
use Session;

/**
 * Class HandleUserSettingsChanged.
 */
class HandleUserSettingsChanged
{
    /**
     * Create the event handler.
     *
     * @param CompanyRepository $companyRepo
     * @param UserMailer $userMailer
     */
    public function __construct(CompanyRepository $companyRepo, UserMailer $userMailer)
    {
        $this->companyRepo = $companyRepo;
        $this->userMailer = $userMailer;
    }

    /**
     * Handle the event.
     *
     * @param UserSettingsChanged $event
     *
     * @return void
     */
    public function handle(UserSettingsChanged $event)
    {
        if (!Auth::check()) {
            return;
        }

        $company = Auth::user()->company;
        $company->loadLocalizationSettings();

        $users = $this->companyRepo->loadCompanys(Auth::user()->id);
        Session::put(SESSION_USER_ACCOUNTS, $users);

        if ($event->user && $event->user->isEmailBeingChanged()) {
            $this->userMailer->sendConfirmation($event->user);
            Session::flash('warning', trans('texts.verify_email'));
        }
    }
}
