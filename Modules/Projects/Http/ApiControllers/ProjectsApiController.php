<?php

namespace Modules\Projects\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Projects\Repositories\ProjectsRepository;
use Modules\Projects\Http\Requests\ProjectsRequest;
use Modules\Projects\Http\Requests\CreateProjectsRequest;
use Modules\Projects\Http\Requests\UpdateProjectsRequest;

class ProjectsApiController extends BaseAPIController
{
    protected $ProjectsRepo;
    protected $entityType = 'projects';

    public function __construct(ProjectsRepository $projectsRepo)
    {
        parent::__construct();

        $this->projectsRepo = $projectsRepo;
    }

    /**
     * @SWG\Get(
     *   path="/projects",
     *   summary="List of projects",
     *   tags={"projects"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with projects",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Projects"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->projectsRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/projects/{projects_id}",
     *   summary="Individual Projects",
     *   tags={"projects"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single projects",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Projects"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(ProjectsRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/projects",
     *   tags={"projects"},
     *   summary="Create a projects",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Projects")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New projects",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Projects"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateProjectsRequest $request)
    {
        $projects = $this->projectsRepo->save($request->input());

        return $this->itemResponse($projects);
    }

    /**
     * @SWG\Put(
     *   path="/projects/{projects_id}",
     *   tags={"projects"},
     *   summary="Update a projects",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Projects")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update projects",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Projects"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateProjectsRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $projects = $this->projectsRepo->save($request->input(), $request->entity());

        return $this->itemResponse($projects);
    }


    /**
     * @SWG\Delete(
     *   path="/projects/{projects_id}",
     *   tags={"projects"},
     *   summary="Delete a projects",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Projects")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete projects",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Projects"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateProjectsRequest $request)
    {
        $projects = $request->entity();

        $this->projectsRepo->delete($projects);

        return $this->itemResponse($projects);
    }

}
