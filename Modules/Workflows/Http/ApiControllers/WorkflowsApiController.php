<?php

namespace Modules\Workflows\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Workflows\Repositories\WorkflowsRepository;
use Modules\Workflows\Http\Requests\WorkflowsRequest;
use Modules\Workflows\Http\Requests\CreateWorkflowsRequest;
use Modules\Workflows\Http\Requests\UpdateWorkflowsRequest;

class WorkflowsApiController extends BaseAPIController
{
    protected $WorkflowsRepo;
    protected $entityType = 'workflows';

    public function __construct(WorkflowsRepository $workflowsRepo)
    {
        parent::__construct();

        $this->workflowsRepo = $workflowsRepo;
    }

    /**
     * @SWG\Get(
     *   path="/workflows",
     *   summary="List of workflows",
     *   tags={"workflows"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with workflows",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Workflows"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->workflowsRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/workflows/{workflows_id}",
     *   summary="Individual Workflows",
     *   tags={"workflows"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single workflows",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Workflows"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(WorkflowsRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/workflows",
     *   tags={"workflows"},
     *   summary="Create a workflows",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Workflows")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New workflows",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Workflows"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateWorkflowsRequest $request)
    {
        $workflows = $this->workflowsRepo->save($request->input());

        return $this->itemResponse($workflows);
    }

    /**
     * @SWG\Put(
     *   path="/workflows/{workflows_id}",
     *   tags={"workflows"},
     *   summary="Update a workflows",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Workflows")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update workflows",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Workflows"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateWorkflowsRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $workflows = $this->workflowsRepo->save($request->input(), $request->entity());

        return $this->itemResponse($workflows);
    }


    /**
     * @SWG\Delete(
     *   path="/workflows/{workflows_id}",
     *   tags={"workflows"},
     *   summary="Delete a workflows",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Workflows")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete workflows",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Workflows"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateWorkflowsRequest $request)
    {
        $workflows = $request->entity();

        $this->workflowsRepo->delete($workflows);

        return $this->itemResponse($workflows);
    }

}
