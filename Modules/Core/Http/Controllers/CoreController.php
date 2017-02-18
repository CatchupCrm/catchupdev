<?php

namespace Modules\Core\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Core\Datatables\CoreDatatable;
use Modules\Core\Repositories\CoreRepository;
use Modules\Core\Http\Requests\CoreRequest;
use Modules\Core\Http\Requests\CreateCoreRequest;
use Modules\Core\Http\Requests\UpdateCoreRequest;

class CoreController extends BaseController
{
    protected $CoreRepo;
    //protected $entityType = 'core';

    public function __construct(CoreRepository $coreRepo)
    {
        //parent::__construct();

        $this->coreRepo = $coreRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'core',
            'datatable' => new CoreDatatable(),
            'title' => mtrans('core', 'core_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new CoreDatatable();
        $query = $this->coreRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(CoreRequest $request)
    {
        $data = [
            'core' => null,
            'method' => 'POST',
            'url' => 'core',
            'title' => mtrans('core', 'new_core'),
        ];

        return view('core::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateCoreRequest $request)
    {
        $core = $this->coreRepo->save($request->input());

        return redirect()->to($core->present()->editUrl)
            ->with('message', mtrans('core', 'created_core'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(CoreRequest $request)
    {
        $core = $request->entity();

        $data = [
            'core' => $core,
            'method' => 'PUT',
            'url' => 'core/' . $core->public_id,
            'title' => mtrans('core', 'edit_core'),
        ];

        return view('core::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(CoreRequest $request)
    {
        return redirect()->to("core/{$request->core}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateCoreRequest $request)
    {
        $core = $this->coreRepo->save($request->input(), $request->entity());

        return redirect()->to($core->present()->editUrl)
            ->with('message', mtrans('core', 'updated_core'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->coreRepo->bulk($ids, $action);

        return redirect()->to('core')
            ->with('message', mtrans('core', $action . '_core_complete'));
    }
}
