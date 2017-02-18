<?php

namespace Modules\Taxes\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Taxes\Repositories\TaxesRepository;
use Modules\Taxes\Http\Requests\TaxesRequest;
use Modules\Taxes\Http\Requests\CreateTaxesRequest;
use Modules\Taxes\Http\Requests\UpdateTaxesRequest;

class TaxesApiController extends BaseAPIController
{
    protected $TaxesRepo;
    protected $entityType = 'taxes';

    public function __construct(TaxesRepository $taxesRepo)
    {
        parent::__construct();

        $this->taxesRepo = $taxesRepo;
    }

    /**
     * @SWG\Get(
     *   path="/taxes",
     *   summary="List of taxes",
     *   tags={"taxes"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with taxes",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Taxes"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->taxesRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/taxes/{taxes_id}",
     *   summary="Individual Taxes",
     *   tags={"taxes"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single taxes",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Taxes"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(TaxesRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/taxes",
     *   tags={"taxes"},
     *   summary="Create a taxes",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Taxes")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New taxes",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Taxes"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateTaxesRequest $request)
    {
        $taxes = $this->taxesRepo->save($request->input());

        return $this->itemResponse($taxes);
    }

    /**
     * @SWG\Put(
     *   path="/taxes/{taxes_id}",
     *   tags={"taxes"},
     *   summary="Update a taxes",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Taxes")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update taxes",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Taxes"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateTaxesRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $taxes = $this->taxesRepo->save($request->input(), $request->entity());

        return $this->itemResponse($taxes);
    }


    /**
     * @SWG\Delete(
     *   path="/taxes/{taxes_id}",
     *   tags={"taxes"},
     *   summary="Delete a taxes",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Taxes")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete taxes",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Taxes"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateTaxesRequest $request)
    {
        $taxes = $request->entity();

        $this->taxesRepo->delete($taxes);

        return $this->itemResponse($taxes);
    }

}
