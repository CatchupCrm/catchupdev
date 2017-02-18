<?php

namespace Modules\Invoices\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Invoices\Datatables\InvoicesDatatable;
use Modules\Invoices\Repositories\InvoicesRepository;
use Modules\Invoices\Http\Requests\InvoicesRequest;
use Modules\Invoices\Http\Requests\CreateInvoicesRequest;
use Modules\Invoices\Http\Requests\UpdateInvoicesRequest;

class InvoicesController extends BaseController
{
    protected $InvoicesRepo;
    //protected $entityType = 'invoices';

    public function __construct(InvoicesRepository $invoicesRepo)
    {
        //parent::__construct();

        $this->invoicesRepo = $invoicesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'invoices',
            'datatable' => new InvoicesDatatable(),
            'title' => mtrans('invoices', 'invoices_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new InvoicesDatatable();
        $query = $this->invoicesRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(InvoicesRequest $request)
    {
        $data = [
            'invoices' => null,
            'method' => 'POST',
            'url' => 'invoices',
            'title' => mtrans('invoices', 'new_invoices'),
        ];

        return view('invoices::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateInvoicesRequest $request)
    {
        $invoices = $this->invoicesRepo->save($request->input());

        return redirect()->to($invoices->present()->editUrl)
            ->with('message', mtrans('invoices', 'created_invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(InvoicesRequest $request)
    {
        $invoices = $request->entity();

        $data = [
            'invoices' => $invoices,
            'method' => 'PUT',
            'url' => 'invoices/' . $invoices->public_id,
            'title' => mtrans('invoices', 'edit_invoices'),
        ];

        return view('invoices::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(InvoicesRequest $request)
    {
        return redirect()->to("invoices/{$request->invoices}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateInvoicesRequest $request)
    {
        $invoices = $this->invoicesRepo->save($request->input(), $request->entity());

        return redirect()->to($invoices->present()->editUrl)
            ->with('message', mtrans('invoices', 'updated_invoices'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->invoicesRepo->bulk($ids, $action);

        return redirect()->to('invoices')
            ->with('message', mtrans('invoices', $action . '_invoices_complete'));
    }
}
