<?php

namespace Modules\Projects\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Projects\Datatables\ProjectsDatatable;
use Modules\Projects\Repositories\ProjectsRepository;
use Modules\Projects\Http\Requests\ProjectsRequest;
use Modules\Projects\Http\Requests\CreateProjectsRequest;
use Modules\Projects\Http\Requests\UpdateProjectsRequest;

class ProjectsController extends BaseController
{
    protected $ProjectsRepo;
    //protected $entityType = 'projects';

    public function __construct(ProjectsRepository $projectsRepo)
    {
        //parent::__construct();

        $this->projectsRepo = $projectsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'projects',
            'datatable' => new ProjectsDatatable(),
            'title' => mtrans('projects', 'projects_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new ProjectsDatatable();
        $query = $this->projectsRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(ProjectsRequest $request)
    {
        $data = [
            'projects' => null,
            'method' => 'POST',
            'url' => 'projects',
            'title' => mtrans('projects', 'new_projects'),
        ];

        return view('projects::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateProjectsRequest $request)
    {
        $projects = $this->projectsRepo->save($request->input());

        return redirect()->to($projects->present()->editUrl)
            ->with('message', mtrans('projects', 'created_projects'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(ProjectsRequest $request)
    {
        $projects = $request->entity();

        $data = [
            'projects' => $projects,
            'method' => 'PUT',
            'url' => 'projects/' . $projects->public_id,
            'title' => mtrans('projects', 'edit_projects'),
        ];

        return view('projects::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(ProjectsRequest $request)
    {
        return redirect()->to("projects/{$request->projects}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateProjectsRequest $request)
    {
        $projects = $this->projectsRepo->save($request->input(), $request->entity());

        return redirect()->to($projects->present()->editUrl)
            ->with('message', mtrans('projects', 'updated_projects'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->projectsRepo->bulk($ids, $action);

        return redirect()->to('projects')
            ->with('message', mtrans('projects', $action . '_projects_complete'));
    }
}
