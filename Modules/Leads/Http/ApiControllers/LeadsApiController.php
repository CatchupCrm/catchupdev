<?php

namespace Modules\Leads\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Leads\Repositories\LeadsRepository;
use Modules\Leads\Http\Requests\LeadsRequest;
use Modules\Leads\Http\Requests\CreateLeadsRequest;
use Modules\Leads\Http\Requests\UpdateLeadsRequest;

class LeadsApiController extends BaseAPIController
{
    protected $LeadsRepo;
    protected $entityType = 'leads';

    public function __construct(LeadsRepository $leadsRepo)
    {
        parent::__construct();

        $this->leadsRepo = $leadsRepo;
    }

    /**
     * @SWG\Get(
     *   path="/leads",
     *   summary="List of leads",
     *   tags={"leads"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with leads",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Leads"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->leadsRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/leads/{leads_id}",
     *   summary="Individual Leads",
     *   tags={"leads"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single leads",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Leads"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(LeadsRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/leads",
     *   tags={"leads"},
     *   summary="Create a leads",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Leads")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New leads",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Leads"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateLeadsRequest $request)
    {
        $leads = $this->leadsRepo->save($request->input());

        return $this->itemResponse($leads);
    }

    /**
     * @SWG\Put(
     *   path="/leads/{leads_id}",
     *   tags={"leads"},
     *   summary="Update a leads",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Leads")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update leads",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Leads"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateLeadsRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $leads = $this->leadsRepo->save($request->input(), $request->entity());

        return $this->itemResponse($leads);
    }


    /**
     * @SWG\Delete(
     *   path="/leads/{leads_id}",
     *   tags={"leads"},
     *   summary="Delete a leads",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Leads")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete leads",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Leads"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateLeadsRequest $request)
    {
        $leads = $request->entity();

        $this->leadsRepo->delete($leads);

        return $this->itemResponse($leads);
    }

}
