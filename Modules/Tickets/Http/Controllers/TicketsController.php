<?php

namespace Modules\Tickets\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Tickets\Datatables\TicketsDatatable;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Tickets\Http\Requests\TicketsRequest;
use Modules\Tickets\Http\Requests\CreateTicketsRequest;
use Modules\Tickets\Http\Requests\UpdateTicketsRequest;

class TicketsController extends BaseController
{
    protected $TicketsRepo;
    //protected $entityType = 'tickets';

    public function __construct(TicketsRepository $ticketsRepo)
    {
        //parent::__construct();

        $this->ticketsRepo = $ticketsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'tickets',
            'datatable' => new TicketsDatatable(),
            'title' => mtrans('tickets', 'tickets_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new TicketsDatatable();
        $query = $this->ticketsRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(TicketsRequest $request)
    {
        $data = [
            'tickets' => null,
            'method' => 'POST',
            'url' => 'tickets',
            'title' => mtrans('tickets', 'new_tickets'),
        ];

        return view('tickets::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateTicketsRequest $request)
    {
        $tickets = $this->ticketsRepo->save($request->input());

        return redirect()->to($tickets->present()->editUrl)
            ->with('message', mtrans('tickets', 'created_tickets'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(TicketsRequest $request)
    {
        $tickets = $request->entity();

        $data = [
            'tickets' => $tickets,
            'method' => 'PUT',
            'url' => 'tickets/' . $tickets->public_id,
            'title' => mtrans('tickets', 'edit_tickets'),
        ];

        return view('tickets::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(TicketsRequest $request)
    {
        return redirect()->to("tickets/{$request->tickets}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateTicketsRequest $request)
    {
        $tickets = $this->ticketsRepo->save($request->input(), $request->entity());

        return redirect()->to($tickets->present()->editUrl)
            ->with('message', mtrans('tickets', 'updated_tickets'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->ticketsRepo->bulk($ids, $action);

        return redirect()->to('tickets')
            ->with('message', mtrans('tickets', $action . '_tickets_complete'));
    }
}
