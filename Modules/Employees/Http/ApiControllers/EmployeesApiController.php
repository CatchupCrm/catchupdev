<?php

namespace Modules\Employees\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Employees\Repositories\EmployeesRepository;
use Modules\Employees\Http\Requests\EmployeesRequest;
use Modules\Employees\Http\Requests\CreateEmployeesRequest;
use Modules\Employees\Http\Requests\UpdateEmployeesRequest;

class EmployeesApiController extends BaseAPIController
{
    protected $EmployeesRepo;
    protected $entityType = 'employees';

    public function __construct(EmployeesRepository $employeesRepo)
    {
        parent::__construct();

        $this->employeesRepo = $employeesRepo;
    }

    /**
     * @SWG\Get(
     *   path="/employees",
     *   summary="List of employees",
     *   tags={"employees"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with employees",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Employees"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->employeesRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/employees/{employees_id}",
     *   summary="Individual Employees",
     *   tags={"employees"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single employees",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Employees"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(EmployeesRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/employees",
     *   tags={"employees"},
     *   summary="Create a employees",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Employees")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New employees",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Employees"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateEmployeesRequest $request)
    {
        $employees = $this->employeesRepo->save($request->input());

        return $this->itemResponse($employees);
    }

    /**
     * @SWG\Put(
     *   path="/employees/{employees_id}",
     *   tags={"employees"},
     *   summary="Update a employees",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Employees")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update employees",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Employees"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateEmployeesRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $employees = $this->employeesRepo->save($request->input(), $request->entity());

        return $this->itemResponse($employees);
    }


    /**
     * @SWG\Delete(
     *   path="/employees/{employees_id}",
     *   tags={"employees"},
     *   summary="Delete a employees",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Employees")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete employees",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Employees"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateEmployeesRequest $request)
    {
        $employees = $request->entity();

        $this->employeesRepo->delete($employees);

        return $this->itemResponse($employees);
    }

}
