<?php

namespace Modules\Relations\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Relations\Repositories\RelationsRepository;
use Modules\Relations\Http\Requests\RelationsRequest;
use Modules\Relations\Http\Requests\CreateRelationsRequest;
use Modules\Relations\Http\Requests\UpdateRelationsRequest;

class RelationsApiController extends BaseAPIController
{
    protected $RelationsRepo;
    protected $entityType = 'relations';

    public function __construct(RelationsRepository $relationsRepo)
    {
        parent::__construct();

        $this->relationsRepo = $relationsRepo;
    }

    /**
     * @SWG\Get(
     *   path="/relations",
     *   summary="List of relations",
     *   tags={"relations"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with relations",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Relations"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->relationsRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/relations/{relations_id}",
     *   summary="Individual Relations",
     *   tags={"relations"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single relations",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Relations"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(RelationsRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/relations",
     *   tags={"relations"},
     *   summary="Create a relations",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Relations")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New relations",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Relations"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateRelationsRequest $request)
    {
        $relations = $this->relationsRepo->save($request->input());

        return $this->itemResponse($relations);
    }

    /**
     * @SWG\Put(
     *   path="/relations/{relations_id}",
     *   tags={"relations"},
     *   summary="Update a relations",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Relations")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update relations",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Relations"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateRelationsRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $relations = $this->relationsRepo->save($request->input(), $request->entity());

        return $this->itemResponse($relations);
    }


    /**
     * @SWG\Delete(
     *   path="/relations/{relations_id}",
     *   tags={"relations"},
     *   summary="Delete a relations",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Relations")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete relations",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Relations"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateRelationsRequest $request)
    {
        $relations = $request->entity();

        $this->relationsRepo->delete($relations);

        return $this->itemResponse($relations);
    }

}
