<?php

namespace Modules\Core\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Core\Repositories\CoreRepository;
use Modules\Core\Http\Requests\CoreRequest;
use Modules\Core\Http\Requests\CreateCoreRequest;
use Modules\Core\Http\Requests\UpdateCoreRequest;

class CoreApiController extends BaseAPIController
{
    protected $CoreRepo;
    protected $entityType = 'core';

    public function __construct(CoreRepository $coreRepo)
    {
        parent::__construct();

        $this->coreRepo = $coreRepo;
    }

    /**
     * @SWG\Get(
     *   path="/core",
     *   summary="List of core",
     *   tags={"core"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with core",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Core"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->coreRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/core/{core_id}",
     *   summary="Individual Core",
     *   tags={"core"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single core",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Core"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(CoreRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/core",
     *   tags={"core"},
     *   summary="Create a core",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Core")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New core",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Core"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateCoreRequest $request)
    {
        $core = $this->coreRepo->save($request->input());

        return $this->itemResponse($core);
    }

    /**
     * @SWG\Put(
     *   path="/core/{core_id}",
     *   tags={"core"},
     *   summary="Update a core",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Core")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update core",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Core"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateCoreRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $core = $this->coreRepo->save($request->input(), $request->entity());

        return $this->itemResponse($core);
    }


    /**
     * @SWG\Delete(
     *   path="/core/{core_id}",
     *   tags={"core"},
     *   summary="Delete a core",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Core")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete core",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Core"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateCoreRequest $request)
    {
        $core = $request->entity();

        $this->coreRepo->delete($core);

        return $this->itemResponse($core);
    }

}
