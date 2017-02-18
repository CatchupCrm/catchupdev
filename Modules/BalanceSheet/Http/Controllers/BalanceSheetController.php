<?php

namespace Modules\BalanceSheet\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\BalanceSheet\Datatables\BalanceSheetDatatable;
use Modules\BalanceSheet\Repositories\BalanceSheetRepository;
use Modules\BalanceSheet\Http\Requests\BalanceSheetRequest;
use Modules\BalanceSheet\Http\Requests\CreateBalanceSheetRequest;
use Modules\BalanceSheet\Http\Requests\UpdateBalanceSheetRequest;

class BalanceSheetController extends BaseController
{
    protected $BalanceSheetRepo;
    //protected $entityType = 'balancesheet';

    public function __construct(BalanceSheetRepository $balancesheetRepo)
    {
        //parent::__construct();

        $this->balancesheetRepo = $balancesheetRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'balancesheet',
            'datatable' => new BalanceSheetDatatable(),
            'title' => mtrans('balancesheet', 'balancesheet_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new BalanceSheetDatatable();
        $query = $this->balancesheetRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(BalanceSheetRequest $request)
    {
        $data = [
            'balancesheet' => null,
            'method' => 'POST',
            'url' => 'balancesheet',
            'title' => mtrans('balancesheet', 'new_balancesheet'),
        ];

        return view('balancesheet::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateBalanceSheetRequest $request)
    {
        $balancesheet = $this->balancesheetRepo->save($request->input());

        return redirect()->to($balancesheet->present()->editUrl)
            ->with('message', mtrans('balancesheet', 'created_balancesheet'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(BalanceSheetRequest $request)
    {
        $balancesheet = $request->entity();

        $data = [
            'balancesheet' => $balancesheet,
            'method' => 'PUT',
            'url' => 'balancesheet/' . $balancesheet->public_id,
            'title' => mtrans('balancesheet', 'edit_balancesheet'),
        ];

        return view('balancesheet::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(BalanceSheetRequest $request)
    {
        return redirect()->to("balancesheet/{$request->balancesheet}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateBalanceSheetRequest $request)
    {
        $balancesheet = $this->balancesheetRepo->save($request->input(), $request->entity());

        return redirect()->to($balancesheet->present()->editUrl)
            ->with('message', mtrans('balancesheet', 'updated_balancesheet'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->balancesheetRepo->bulk($ids, $action);

        return redirect()->to('balancesheet')
            ->with('message', mtrans('balancesheet', $action . '_balancesheet_complete'));
    }
}
