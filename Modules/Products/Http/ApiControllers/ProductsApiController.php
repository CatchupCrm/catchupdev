<?php

namespace Modules\Products\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Products\Repositories\ProductsRepository;
use Modules\Products\Http\Requests\ProductsRequest;
use Modules\Products\Http\Requests\CreateProductsRequest;
use Modules\Products\Http\Requests\UpdateProductsRequest;

class ProductsApiController extends BaseAPIController
{
    protected $ProductsRepo;
    protected $entityType = 'products';

    public function __construct(ProductsRepository $productsRepo)
    {
        parent::__construct();

        $this->productsRepo = $productsRepo;
    }

    /**
     * @SWG\Get(
     *   path="/products",
     *   summary="List of products",
     *   tags={"products"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with products",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Products"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->productsRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/products/{products_id}",
     *   summary="Individual Products",
     *   tags={"products"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single products",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Products"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(ProductsRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/products",
     *   tags={"products"},
     *   summary="Create a products",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Products")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New products",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Products"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateProductsRequest $request)
    {
        $products = $this->productsRepo->save($request->input());

        return $this->itemResponse($products);
    }

    /**
     * @SWG\Put(
     *   path="/products/{products_id}",
     *   tags={"products"},
     *   summary="Update a products",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Products")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update products",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Products"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateProductsRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $products = $this->productsRepo->save($request->input(), $request->entity());

        return $this->itemResponse($products);
    }


    /**
     * @SWG\Delete(
     *   path="/products/{products_id}",
     *   tags={"products"},
     *   summary="Delete a products",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Products")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete products",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Products"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateProductsRequest $request)
    {
        $products = $request->entity();

        $this->productsRepo->delete($products);

        return $this->itemResponse($products);
    }

}
