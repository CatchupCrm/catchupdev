<?php

namespace Modules\Taxes\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Taxes\Datatables\TaxesDatatable;
use Modules\Taxes\Repositories\TaxesRepository;
use Modules\Taxes\Http\Requests\TaxesRequest;
use Modules\Taxes\Http\Requests\CreateTaxesRequest;
use Modules\Taxes\Http\Requests\UpdateTaxesRequest;

class TaxesController extends BaseController
{
    protected $TaxesRepo;
    //protected $entityType = 'taxes';

    public function __construct(TaxesRepository $taxesRepo)
    {
        //parent::__construct();

        $this->taxesRepo = $taxesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'taxes',
            'datatable' => new TaxesDatatable(),
            'title' => mtrans('taxes', 'taxes_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new TaxesDatatable();
        $query = $this->taxesRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(TaxesRequest $request)
    {
        $data = [
            'taxes' => null,
            'method' => 'POST',
            'url' => 'taxes',
            'title' => mtrans('taxes', 'new_taxes'),
        ];

        return view('taxes::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateTaxesRequest $request)
    {
        $taxes = $this->taxesRepo->save($request->input());

        return redirect()->to($taxes->present()->editUrl)
            ->with('message', mtrans('taxes', 'created_taxes'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(TaxesRequest $request)
    {
        $taxes = $request->entity();

        $data = [
            'taxes' => $taxes,
            'method' => 'PUT',
            'url' => 'taxes/' . $taxes->public_id,
            'title' => mtrans('taxes', 'edit_taxes'),
        ];

        return view('taxes::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(TaxesRequest $request)
    {
        return redirect()->to("taxes/{$request->taxes}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateTaxesRequest $request)
    {
        $taxes = $this->taxesRepo->save($request->input(), $request->entity());

        return redirect()->to($taxes->present()->editUrl)
            ->with('message', mtrans('taxes', 'updated_taxes'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->taxesRepo->bulk($ids, $action);

        return redirect()->to('taxes')
            ->with('message', mtrans('taxes', $action . '_taxes_complete'));
    }
}
