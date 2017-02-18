<?php

namespace Modules\BalanceSheet\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Balancesheet\Repositories\BalancesheetRepository;
use Modules\Balancesheet\Http\Requests\BalancesheetRequest;
use Modules\Balancesheet\Http\Requests\CreateBalancesheetRequest;
use Modules\Balancesheet\Http\Requests\UpdateBalancesheetRequest;

class BalancesheetApiController extends BaseAPIController
{
    protected $BalancesheetRepo;
    protected $entityType = 'balancesheet';

    public function __construct(BalancesheetRepository $balancesheetRepo)
    {
        parent::__construct();

        $this->balancesheetRepo = $balancesheetRepo;
    }

    /**
     * @SWG\Get(
     *   path="/balancesheet",
     *   summary="List of balancesheet",
     *   tags={"balancesheet"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with balancesheet",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Balancesheet"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->balancesheetRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/balancesheet/{balancesheet_id}",
     *   summary="Individual Balancesheet",
     *   tags={"balancesheet"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single balancesheet",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Balancesheet"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(BalancesheetRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/balancesheet",
     *   tags={"balancesheet"},
     *   summary="Create a balancesheet",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Balancesheet")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New balancesheet",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Balancesheet"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateBalancesheetRequest $request)
    {
        $balancesheet = $this->balancesheetRepo->save($request->input());

        return $this->itemResponse($balancesheet);
    }

    /**
     * @SWG\Put(
     *   path="/balancesheet/{balancesheet_id}",
     *   tags={"balancesheet"},
     *   summary="Update a balancesheet",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Balancesheet")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update balancesheet",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Balancesheet"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateBalancesheetRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $balancesheet = $this->balancesheetRepo->save($request->input(), $request->entity());

        return $this->itemResponse($balancesheet);
    }


    /**
     * @SWG\Delete(
     *   path="/balancesheet/{balancesheet_id}",
     *   tags={"balancesheet"},
     *   summary="Delete a balancesheet",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Balancesheet")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete balancesheet",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Balancesheet"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateBalancesheetRequest $request)
    {
        $balancesheet = $request->entity();

        $this->balancesheetRepo->delete($balancesheet);

        return $this->itemResponse($balancesheet);
    }

}
