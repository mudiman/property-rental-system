<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateServiceFeeTypeAPIRequest;
use App\Http\Requests\API\UpdateServiceFeeTypeAPIRequest;
use App\Models\ServiceFeeType;
use App\Repositories\ServiceFeeTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ServiceFeeTypeController
 * @package App\Http\Controllers\API
 */

class ServiceFeeTypeAPIController extends AppBaseController
{
    /** @var  ServiceFeeTypeRepository */
    private $serviceFeeTypeRepository;

    public function __construct(ServiceFeeTypeRepository $serviceFeeTypeRepo)
    {
        $this->serviceFeeTypeRepository = $serviceFeeTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceFeeTypes",
     *      summary="Get a listing of the ServiceFeeTypes.",
     *      tags={"ServiceFeeType"},
     *      description="Get all ServiceFeeTypes",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/ServiceFeeType")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->serviceFeeTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->serviceFeeTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->serviceFeeTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $serviceFeeTypes = $this->serviceFeeTypeRepository->all();

        return $this->sendResponse($serviceFeeTypes->toArray(), 'Service Fee Types retrieved successfully');
    }
}
