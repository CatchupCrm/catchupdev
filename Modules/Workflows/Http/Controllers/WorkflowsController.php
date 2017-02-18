<?php

namespace Modules\Workflows\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Workflows\Datatables\WorkflowsDatatable;
use Modules\Workflows\Repositories\WorkflowsRepository;
use Modules\Workflows\Http\Requests\WorkflowsRequest;
use Modules\Workflows\Http\Requests\CreateWorkflowsRequest;
use Modules\Workflows\Http\Requests\UpdateWorkflowsRequest;

class WorkflowsController extends BaseController
{
    protected $WorkflowsRepo;
    //protected $entityType = 'workflows';

    public function __construct(WorkflowsRepository $workflowsRepo)
    {
        //parent::__construct();

        $this->workflowsRepo = $workflowsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'workflows',
            'datatable' => new WorkflowsDatatable(),
            'title' => mtrans('workflows', 'workflows_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new WorkflowsDatatable();
        $query = $this->workflowsRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(WorkflowsRequest $request)
    {
        $data = [
            'workflows' => null,
            'method' => 'POST',
            'url' => 'workflows',
            'title' => mtrans('workflows', 'new_workflows'),
        ];

        return view('workflows::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateWorkflowsRequest $request)
    {
        $workflows = $this->workflowsRepo->save($request->input());

        return redirect()->to($workflows->present()->editUrl)
            ->with('message', mtrans('workflows', 'created_workflows'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(WorkflowsRequest $request)
    {
        $workflows = $request->entity();

        $data = [
            'workflows' => $workflows,
            'method' => 'PUT',
            'url' => 'workflows/' . $workflows->public_id,
            'title' => mtrans('workflows', 'edit_workflows'),
        ];

        return view('workflows::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(WorkflowsRequest $request)
    {
        return redirect()->to("workflows/{$request->workflows}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateWorkflowsRequest $request)
    {
        $workflows = $this->workflowsRepo->save($request->input(), $request->entity());

        return redirect()->to($workflows->present()->editUrl)
            ->with('message', mtrans('workflows', 'updated_workflows'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->workflowsRepo->bulk($ids, $action);

        return redirect()->to('workflows')
            ->with('message', mtrans('workflows', $action . '_workflows_complete'));
    }
}
