<?php

namespace Modules\Banking\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Banking\Datatables\BankingDatatable;
use Modules\Banking\Repositories\BankingRepository;
use Modules\Banking\Http\Requests\BankingRequest;
use Modules\Banking\Http\Requests\CreateBankingRequest;
use Modules\Banking\Http\Requests\UpdateBankingRequest;

class BankingController extends BaseController
{
    protected $BankingRepo;
    //protected $entityType = 'banking';

    public function __construct(BankingRepository $bankingRepo)
    {
        //parent::__construct();

        $this->bankingRepo = $bankingRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'banking',
            'datatable' => new BankingDatatable(),
            'title' => mtrans('banking', 'banking_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new BankingDatatable();
        $query = $this->bankingRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(BankingRequest $request)
    {
        $data = [
            'banking' => null,
            'method' => 'POST',
            'url' => 'banking',
            'title' => mtrans('banking', 'new_banking'),
        ];

        return view('banking::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateBankingRequest $request)
    {
        $banking = $this->bankingRepo->save($request->input());

        return redirect()->to($banking->present()->editUrl)
            ->with('message', mtrans('banking', 'created_banking'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(BankingRequest $request)
    {
        $banking = $request->entity();

        $data = [
            'banking' => $banking,
            'method' => 'PUT',
            'url' => 'banking/' . $banking->public_id,
            'title' => mtrans('banking', 'edit_banking'),
        ];

        return view('banking::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(BankingRequest $request)
    {
        return redirect()->to("banking/{$request->banking}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateBankingRequest $request)
    {
        $banking = $this->bankingRepo->save($request->input(), $request->entity());

        return redirect()->to($banking->present()->editUrl)
            ->with('message', mtrans('banking', 'updated_banking'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->bankingRepo->bulk($ids, $action);

        return redirect()->to('banking')
            ->with('message', mtrans('banking', $action . '_banking_complete'));
    }
}
