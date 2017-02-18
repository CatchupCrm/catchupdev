<?php

namespace Modules\Banking\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Banking\Repositories\BankingRepository;
use Modules\Banking\Http\Requests\BankingRequest;
use Modules\Banking\Http\Requests\CreateBankingRequest;
use Modules\Banking\Http\Requests\UpdateBankingRequest;

class BankingApiController extends BaseAPIController
{
    protected $BankingRepo;
    protected $entityType = 'banking';

    public function __construct(BankingRepository $bankingRepo)
    {
        parent::__construct();

        $this->bankingRepo = $bankingRepo;
    }

    /**
     * @SWG\Get(
     *   path="/banking",
     *   summary="List of banking",
     *   tags={"banking"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with banking",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Banking"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->bankingRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/banking/{banking_id}",
     *   summary="Individual Banking",
     *   tags={"banking"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single banking",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Banking"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(BankingRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/banking",
     *   tags={"banking"},
     *   summary="Create a banking",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Banking")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New banking",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Banking"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateBankingRequest $request)
    {
        $banking = $this->bankingRepo->save($request->input());

        return $this->itemResponse($banking);
    }

    /**
     * @SWG\Put(
     *   path="/banking/{banking_id}",
     *   tags={"banking"},
     *   summary="Update a banking",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Banking")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update banking",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Banking"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateBankingRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $banking = $this->bankingRepo->save($request->input(), $request->entity());

        return $this->itemResponse($banking);
    }


    /**
     * @SWG\Delete(
     *   path="/banking/{banking_id}",
     *   tags={"banking"},
     *   summary="Delete a banking",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Banking")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete banking",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Banking"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateBankingRequest $request)
    {
        $banking = $request->entity();

        $this->bankingRepo->delete($banking);

        return $this->itemResponse($banking);
    }

}
