<?php

namespace Modules\Tickets\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Tickets\Http\Requests\TicketsRequest;
use Modules\Tickets\Http\Requests\CreateTicketsRequest;
use Modules\Tickets\Http\Requests\UpdateTicketsRequest;

class TicketsApiController extends BaseAPIController
{
    protected $TicketsRepo;
    protected $entityType = 'tickets';

    public function __construct(TicketsRepository $ticketsRepo)
    {
        parent::__construct();

        $this->ticketsRepo = $ticketsRepo;
    }

    /**
     * @SWG\Get(
     *   path="/tickets",
     *   summary="List of tickets",
     *   tags={"tickets"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with tickets",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Tickets"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->ticketsRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/tickets/{tickets_id}",
     *   summary="Individual Tickets",
     *   tags={"tickets"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single tickets",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Tickets"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(TicketsRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/tickets",
     *   tags={"tickets"},
     *   summary="Create a tickets",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Tickets")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New tickets",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Tickets"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateTicketsRequest $request)
    {
        $tickets = $this->ticketsRepo->save($request->input());

        return $this->itemResponse($tickets);
    }

    /**
     * @SWG\Put(
     *   path="/tickets/{tickets_id}",
     *   tags={"tickets"},
     *   summary="Update a tickets",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Tickets")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update tickets",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Tickets"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateTicketsRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $tickets = $this->ticketsRepo->save($request->input(), $request->entity());

        return $this->itemResponse($tickets);
    }


    /**
     * @SWG\Delete(
     *   path="/tickets/{tickets_id}",
     *   tags={"tickets"},
     *   summary="Delete a tickets",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Tickets")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete tickets",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Tickets"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateTicketsRequest $request)
    {
        $tickets = $request->entity();

        $this->ticketsRepo->delete($tickets);

        return $this->itemResponse($tickets);
    }

}
