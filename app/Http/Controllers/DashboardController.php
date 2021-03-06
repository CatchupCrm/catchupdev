<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expense;
use App\Ninja\Repositories\DashboardRepository;
use Auth;
use Utils;
use View;

/**
 * Class DashboardController.
 */
class DashboardController extends BaseController
{
    public function __construct(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $viewAll = $user->hasPermission('view_all');
        $userId = $user->id;
        $company = $user->company;
        $companyId = $company->id;

        $dashboardRepo = $this->dashboardRepo;
        $metrics = $dashboardRepo->totals($companyId, $userId, $viewAll);
        $paidToDate = $dashboardRepo->paidToDate($company, $userId, $viewAll);
        $averageInvoice = $dashboardRepo->averages($company, $userId, $viewAll);
        $balances = $dashboardRepo->balances($companyId, $userId, $viewAll);
        $activities = $dashboardRepo->activities($companyId, $userId, $viewAll);
        $pastDue = $dashboardRepo->pastDue($companyId, $userId, $viewAll);
        $upcoming = $dashboardRepo->upcoming($companyId, $userId, $viewAll);
        $payments = $dashboardRepo->payments($companyId, $userId, $viewAll);
        $expenses = $dashboardRepo->expenses($company, $userId, $viewAll);
        $tasks = $dashboardRepo->tasks($companyId, $userId, $viewAll);

        $showBlueVinePromo = $user->is_admin
            && env('BLUEVINE_PARTNER_UNIQUE_ID')
            && !$company->corporation->bluevine_status
            && $company->created_at <= date('Y-m-d', strtotime('-1 month'));

        $showWhiteLabelExpired = false;

        // check if the company has quotes
        $hasQuotes = false;
        foreach ([$upcoming, $pastDue] as $data) {
            foreach ($data as $invoice) {
                if ($invoice->invoice_type_id == INVOICE_TYPE_QUOTE) {
                    $hasQuotes = true;
                }
            }
        }

        $data = [
            'company' => $user->company,
            'user' => $user,
            'paidToDate' => $paidToDate,
            'balances' => $balances,
            'averageInvoice' => $averageInvoice,
            'invoicesSent' => $metrics ? $metrics->invoices_sent : 0,
            'activeClients' => $metrics ? $metrics->active_clients : 0,
            'activities' => $activities,
            'pastDue' => $pastDue,
            'upcoming' => $upcoming,
            'payments' => $payments,
            'title' => trans('texts.dashboard'),
            'hasQuotes' => $hasQuotes,
            'showBreadcrumbs' => false,
            'currencies' => $this->getCurrencyCodes(),
            'expenses' => $expenses,
            'tasks' => $tasks,
            'showBlueVinePromo' => $showBlueVinePromo,
            'showWhiteLabelExpired' => $showWhiteLabelExpired,
        ];

        if ($showBlueVinePromo) {
            $usdLast12Months = 0;
            $pastYear = date('Y-m-d', strtotime('-1 year'));
            $paidLast12Months = $dashboardRepo->paidToDate($company, $userId, $viewAll, $pastYear);

            foreach ($paidLast12Months as $item) {
                if ($item->currency_id == null) {
                    $currency = $user->company->currency_id ?: DEFAULT_CURRENCY;
                } else {
                    $currency = $item->currency_id;
                }

                if ($currency == CURRENCY_DOLLAR) {
                    $usdLast12Months += $item->value;
                }
            }

            $data['usdLast12Months'] = $usdLast12Months;
        }

        return View::make('dashboard', $data);
    }

    private function getCurrencyCodes()
    {
        $company = Auth::user()->company;
        $currencyIds = $company->currency_id ? [$company->currency_id] : [DEFAULT_CURRENCY];

        // get client/invoice currencies
        $data = Client::scope()
            ->withArchived()
            ->distinct()
            ->get(['currency_id'])
            ->toArray();

        array_map(function ($item) use (&$currencyIds) {
            $currencyId = intval($item['currency_id']);
            if ($currencyId && !in_array($currencyId, $currencyIds)) {
                $currencyIds[] = $currencyId;
            }
        }, $data);

        // get expense currencies
        $data = Expense::scope()
            ->withArchived()
            ->distinct()
            ->get(['expense_currency_id'])
            ->toArray();

        array_map(function ($item) use (&$currencyIds) {
            $currencyId = intval($item['expense_currency_id']);
            if ($currencyId && !in_array($currencyId, $currencyIds)) {
                $currencyIds[] = $currencyId;
            }
        }, $data);

        $currencies = [];
        foreach ($currencyIds as $currencyId) {
            $currencies[$currencyId] = Utils::getFromCache($currencyId, 'currencies')->code;
        }

        return $currencies;
    }

    public function chartData($groupBy, $startDate, $endDate, $currencyCode, $includeExpenses)
    {
        $includeExpenses = filter_var($includeExpenses, FILTER_VALIDATE_BOOLEAN);
        $data = $this->dashboardRepo->chartData(Auth::user()->company, $groupBy, $startDate, $endDate, $currencyCode, $includeExpenses);

        return json_encode($data);
    }
}
