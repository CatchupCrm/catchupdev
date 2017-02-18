<?php

namespace Modules\Relations\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Relations\Datatables\RelationsDatatable;
use Modules\Relations\Repositories\RelationsRepository;
use Modules\Relations\Http\Requests\RelationsRequest;
use Modules\Relations\Http\Requests\CreateRelationsRequest;
use Modules\Relations\Http\Requests\UpdateRelationsRequest;

class RelationsController extends BaseController
{
    protected $RelationsRepo;
    //protected $entityType = 'relations';

    public function __construct(RelationsRepository $relationsRepo)
    {
        //parent::__construct();

        $this->relationsRepo = $relationsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'relations',
            'datatable' => new RelationsDatatable(),
            'title' => mtrans('relations', 'relations_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new RelationsDatatable();
        $query = $this->relationsRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(RelationsRequest $request)
    {
        $data = [
            'relations' => null,
            'method' => 'POST',
            'url' => 'relations',
            'title' => mtrans('relations', 'new_relations'),
        ];

        return view('relations::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateRelationsRequest $request)
    {
        $relations = $this->relationsRepo->save($request->input());

        return redirect()->to($relations->present()->editUrl)
            ->with('message', mtrans('relations', 'created_relations'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(RelationsRequest $request)
    {
        $relations = $request->entity();

        $data = [
            'relations' => $relations,
            'method' => 'PUT',
            'url' => 'relations/' . $relations->public_id,
            'title' => mtrans('relations', 'edit_relations'),
        ];

        return view('relations::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(RelationsRequest $request)
    {
        return redirect()->to("relations/{$request->relations}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateRelationsRequest $request)
    {
        $relations = $this->relationsRepo->save($request->input(), $request->entity());

        return redirect()->to($relations->present()->editUrl)
            ->with('message', mtrans('relations', 'updated_relations'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->relationsRepo->bulk($ids, $action);

        return redirect()->to('relations')
            ->with('message', mtrans('relations', $action . '_relations_complete'));
    }
}
