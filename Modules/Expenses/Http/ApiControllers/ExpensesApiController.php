<?php

namespace Modules\Expenses\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Expenses\Repositories\ExpensesRepository;
use Modules\Expenses\Http\Requests\ExpensesRequest;
use Modules\Expenses\Http\Requests\CreateExpensesRequest;
use Modules\Expenses\Http\Requests\UpdateExpensesRequest;

class ExpensesApiController extends BaseAPIController
{
    protected $ExpensesRepo;
    protected $entityType = 'expenses';

    public function __construct(ExpensesRepository $expensesRepo)
    {
        parent::__construct();

        $this->expensesRepo = $expensesRepo;
    }

    /**
     * @SWG\Get(
     *   path="/expenses",
     *   summary="List of expenses",
     *   tags={"expenses"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with expenses",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Expenses"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->expensesRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/expenses/{expenses_id}",
     *   summary="Individual Expenses",
     *   tags={"expenses"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single expenses",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Expenses"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(ExpensesRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/expenses",
     *   tags={"expenses"},
     *   summary="Create a expenses",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Expenses")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New expenses",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Expenses"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateExpensesRequest $request)
    {
        $expenses = $this->expensesRepo->save($request->input());

        return $this->itemResponse($expenses);
    }

    /**
     * @SWG\Put(
     *   path="/expenses/{expenses_id}",
     *   tags={"expenses"},
     *   summary="Update a expenses",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Expenses")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update expenses",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Expenses"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateExpensesRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $expenses = $this->expensesRepo->save($request->input(), $request->entity());

        return $this->itemResponse($expenses);
    }


    /**
     * @SWG\Delete(
     *   path="/expenses/{expenses_id}",
     *   tags={"expenses"},
     *   summary="Delete a expenses",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Expenses")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete expenses",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Expenses"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateExpensesRequest $request)
    {
        $expenses = $request->entity();

        $this->expensesRepo->delete($expenses);

        return $this->itemResponse($expenses);
    }

}
