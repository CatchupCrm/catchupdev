<?php

namespace Modules\Products\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Products\Datatables\ProductsDatatable;
use Modules\Products\Repositories\ProductsRepository;
use Modules\Products\Http\Requests\ProductsRequest;
use Modules\Products\Http\Requests\CreateProductsRequest;
use Modules\Products\Http\Requests\UpdateProductsRequest;

class ProductsController extends BaseController
{
    protected $ProductsRepo;
    //protected $entityType = 'products';

    public function __construct(ProductsRepository $productsRepo)
    {
        //parent::__construct();

        $this->productsRepo = $productsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'products',
            'datatable' => new ProductsDatatable(),
            'title' => mtrans('products', 'products_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new ProductsDatatable();
        $query = $this->productsRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(ProductsRequest $request)
    {
        $data = [
            'products' => null,
            'method' => 'POST',
            'url' => 'products',
            'title' => mtrans('products', 'new_products'),
        ];

        return view('products::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateProductsRequest $request)
    {
        $products = $this->productsRepo->save($request->input());

        return redirect()->to($products->present()->editUrl)
            ->with('message', mtrans('products', 'created_products'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(ProductsRequest $request)
    {
        $products = $request->entity();

        $data = [
            'products' => $products,
            'method' => 'PUT',
            'url' => 'products/' . $products->public_id,
            'title' => mtrans('products', 'edit_products'),
        ];

        return view('products::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(ProductsRequest $request)
    {
        return redirect()->to("products/{$request->products}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateProductsRequest $request)
    {
        $products = $this->productsRepo->save($request->input(), $request->entity());

        return redirect()->to($products->present()->editUrl)
            ->with('message', mtrans('products', 'updated_products'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->productsRepo->bulk($ids, $action);

        return redirect()->to('products')
            ->with('message', mtrans('products', $action . '_products_complete'));
    }
}
