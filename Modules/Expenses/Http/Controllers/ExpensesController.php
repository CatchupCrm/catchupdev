<?php

namespace Modules\Expenses\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Expenses\Datatables\ExpensesDatatable;
use Modules\Expenses\Repositories\ExpensesRepository;
use Modules\Expenses\Http\Requests\ExpensesRequest;
use Modules\Expenses\Http\Requests\CreateExpensesRequest;
use Modules\Expenses\Http\Requests\UpdateExpensesRequest;

class ExpensesController extends BaseController
{
    protected $ExpensesRepo;
    //protected $entityType = 'expenses';

    public function __construct(ExpensesRepository $expensesRepo)
    {
        //parent::__construct();

        $this->expensesRepo = $expensesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'expenses',
            'datatable' => new ExpensesDatatable(),
            'title' => mtrans('expenses', 'expenses_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new ExpensesDatatable();
        $query = $this->expensesRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(ExpensesRequest $request)
    {
        $data = [
            'expenses' => null,
            'method' => 'POST',
            'url' => 'expenses',
            'title' => mtrans('expenses', 'new_expenses'),
        ];

        return view('expenses::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateExpensesRequest $request)
    {
        $expenses = $this->expensesRepo->save($request->input());

        return redirect()->to($expenses->present()->editUrl)
            ->with('message', mtrans('expenses', 'created_expenses'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(ExpensesRequest $request)
    {
        $expenses = $request->entity();

        $data = [
            'expenses' => $expenses,
            'method' => 'PUT',
            'url' => 'expenses/' . $expenses->public_id,
            'title' => mtrans('expenses', 'edit_expenses'),
        ];

        return view('expenses::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(ExpensesRequest $request)
    {
        return redirect()->to("expenses/{$request->expenses}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateExpensesRequest $request)
    {
        $expenses = $this->expensesRepo->save($request->input(), $request->entity());

        return redirect()->to($expenses->present()->editUrl)
            ->with('message', mtrans('expenses', 'updated_expenses'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->expensesRepo->bulk($ids, $action);

        return redirect()->to('expenses')
            ->with('message', mtrans('expenses', $action . '_expenses_complete'));
    }
}
