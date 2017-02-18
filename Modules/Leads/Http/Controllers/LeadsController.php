<?php

namespace Modules\Leads\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Leads\Datatables\LeadsDatatable;
use Modules\Leads\Repositories\LeadsRepository;
use Modules\Leads\Http\Requests\LeadsRequest;
use Modules\Leads\Http\Requests\CreateLeadsRequest;
use Modules\Leads\Http\Requests\UpdateLeadsRequest;

class LeadsController extends BaseController
{
    protected $LeadsRepo;
    //protected $entityType = 'leads';

    public function __construct(LeadsRepository $leadsRepo)
    {
        //parent::__construct();

        $this->leadsRepo = $leadsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'leads',
            'datatable' => new LeadsDatatable(),
            'title' => mtrans('leads', 'leads_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new LeadsDatatable();
        $query = $this->leadsRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(LeadsRequest $request)
    {
        $data = [
            'leads' => null,
            'method' => 'POST',
            'url' => 'leads',
            'title' => mtrans('leads', 'new_leads'),
        ];

        return view('leads::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateLeadsRequest $request)
    {
        $leads = $this->leadsRepo->save($request->input());

        return redirect()->to($leads->present()->editUrl)
            ->with('message', mtrans('leads', 'created_leads'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(LeadsRequest $request)
    {
        $leads = $request->entity();

        $data = [
            'leads' => $leads,
            'method' => 'PUT',
            'url' => 'leads/' . $leads->public_id,
            'title' => mtrans('leads', 'edit_leads'),
        ];

        return view('leads::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(LeadsRequest $request)
    {
        return redirect()->to("leads/{$request->leads}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateLeadsRequest $request)
    {
        $leads = $this->leadsRepo->save($request->input(), $request->entity());

        return redirect()->to($leads->present()->editUrl)
            ->with('message', mtrans('leads', 'updated_leads'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->leadsRepo->bulk($ids, $action);

        return redirect()->to('leads')
            ->with('message', mtrans('leads', $action . '_leads_complete'));
    }
}
