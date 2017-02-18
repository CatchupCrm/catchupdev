<?php

namespace Modules\Invoices\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Invoices\Repositories\InvoicesRepository;
use Modules\Invoices\Http\Requests\InvoicesRequest;
use Modules\Invoices\Http\Requests\CreateInvoicesRequest;
use Modules\Invoices\Http\Requests\UpdateInvoicesRequest;

class InvoicesApiController extends BaseAPIController
{
    protected $InvoicesRepo;
    protected $entityType = 'invoices';

    public function __construct(InvoicesRepository $invoicesRepo)
    {
        parent::__construct();

        $this->invoicesRepo = $invoicesRepo;
    }

    /**
     * @SWG\Get(
     *   path="/invoices",
     *   summary="List of invoices",
     *   tags={"invoices"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with invoices",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Invoices"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->invoicesRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/invoices/{invoices_id}",
     *   summary="Individual Invoices",
     *   tags={"invoices"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single invoices",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Invoices"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(InvoicesRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/invoices",
     *   tags={"invoices"},
     *   summary="Create a invoices",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Invoices")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New invoices",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Invoices"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateInvoicesRequest $request)
    {
        $invoices = $this->invoicesRepo->save($request->input());

        return $this->itemResponse($invoices);
    }

    /**
     * @SWG\Put(
     *   path="/invoices/{invoices_id}",
     *   tags={"invoices"},
     *   summary="Update a invoices",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Invoices")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update invoices",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Invoices"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateInvoicesRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $invoices = $this->invoicesRepo->save($request->input(), $request->entity());

        return $this->itemResponse($invoices);
    }


    /**
     * @SWG\Delete(
     *   path="/invoices/{invoices_id}",
     *   tags={"invoices"},
     *   summary="Delete a invoices",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Invoices")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete invoices",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Invoices"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateInvoicesRequest $request)
    {
        $invoices = $request->entity();

        $this->invoicesRepo->delete($invoices);

        return $this->itemResponse($invoices);
    }

}
