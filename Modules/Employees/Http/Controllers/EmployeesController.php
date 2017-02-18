<?php

namespace Modules\Employees\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Employees\Datatables\EmployeesDatatable;
use Modules\Employees\Repositories\EmployeesRepository;
use Modules\Employees\Http\Requests\EmployeesRequest;
use Modules\Employees\Http\Requests\CreateEmployeesRequest;
use Modules\Employees\Http\Requests\UpdateEmployeesRequest;

class EmployeesController extends BaseController
{
    protected $EmployeesRepo;
    //protected $entityType = 'employees';

    public function __construct(EmployeesRepository $employeesRepo)
    {
        //parent::__construct();

        $this->employeesRepo = $employeesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'employees',
            'datatable' => new EmployeesDatatable(),
            'title' => mtrans('employees', 'employees_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new EmployeesDatatable();
        $query = $this->employeesRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(EmployeesRequest $request)
    {
        $data = [
            'employees' => null,
            'method' => 'POST',
            'url' => 'employees',
            'title' => mtrans('employees', 'new_employees'),
        ];

        return view('employees::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateEmployeesRequest $request)
    {
        $employees = $this->employeesRepo->save($request->input());

        return redirect()->to($employees->present()->editUrl)
            ->with('message', mtrans('employees', 'created_employees'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(EmployeesRequest $request)
    {
        $employees = $request->entity();

        $data = [
            'employees' => $employees,
            'method' => 'PUT',
            'url' => 'employees/' . $employees->public_id,
            'title' => mtrans('employees', 'edit_employees'),
        ];

        return view('employees::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(EmployeesRequest $request)
    {
        return redirect()->to("employees/{$request->employees}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateEmployeesRequest $request)
    {
        $employees = $this->employeesRepo->save($request->input(), $request->entity());

        return redirect()->to($employees->present()->editUrl)
            ->with('message', mtrans('employees', 'updated_employees'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->employeesRepo->bulk($ids, $action);

        return redirect()->to('employees')
            ->with('message', mtrans('employees', $action . '_employees_complete'));
    }
}
