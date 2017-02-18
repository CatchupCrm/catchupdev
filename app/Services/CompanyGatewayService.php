<?php

namespace App\Services;

use App\Ninja\Datatables\CompanyGatewayDatatable;
use App\Ninja\Repositories\CompanyGatewayRepository;

/**
 * Class CompanyGatewayService.
 */
class CompanyGatewayService extends BaseService
{
    /**
     * @var CompanyGatewayRepository
     */
    protected $companyGatewayRepo;

    /**
     * @var DatatableService
     */
    protected $datatableService;

    /**
     * CompanyGatewayService constructor.
     *
     * @param CompanyGatewayRepository $companyGatewayRepo
     * @param DatatableService         $datatableService
     */
    public function __construct(CompanyGatewayRepository $companyGatewayRepo, DatatableService $datatableService)
    {
        $this->companyGatewayRepo = $companyGatewayRepo;
        $this->datatableService = $datatableService;
    }

    /**
     * @return CompanyGatewayRepository
     */
    protected function getRepo()
    {
        return $this->companyGatewayRepo;
    }

    /**
     * @param $companyId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatable($companyId)
    {
        $query = $this->companyGatewayRepo->find($companyId);

        return $this->datatableService->createDatatable(new CompanyGatewayDatatable(false), $query);
    }
}
