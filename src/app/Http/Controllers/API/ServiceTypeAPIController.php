<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateServiceTypeAPIRequest;
use App\Http\Requests\API\UpdateServiceTypeAPIRequest;
use App\Models\ServiceType;
use App\Repositories\ServiceTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ServiceTypeController
 * @package App\Http\Controllers\API
 */

class ServiceTypeAPIController extends AppBaseController
{
    /** @var  ServiceTypeRepository */
    private $serviceTypeRepository;

    public function __construct(ServiceTypeRepository $serviceTypeRepo)
    {
        $this->serviceTypeRepository = $serviceTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceTypes",
     *      summary="Get a listing of the ServiceTypes.",
     *      tags={"ServiceType"},
     *      description="Get all ServiceTypes",
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
     *                  @SWG\Items(ref="#/definitions/ServiceType")
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
        $this->serviceTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->serviceTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->serviceTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $serviceTypes = $this->serviceTypeRepository->all();

        return $this->sendResponse($serviceTypes->toArray(), 'Service Types retrieved successfully');
    }

    /**
     * @param CreateServiceTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/serviceTypes",
     *      summary="Store a newly created ServiceType in storage",
     *      tags={"ServiceType"},
     *      description="Store ServiceType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ServiceType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ServiceType")
     *      ),
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
     *                  ref="#/definitions/ServiceType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateServiceTypeAPIRequest $request)
    {
        $input = $request->all();

        $serviceTypes = $this->serviceTypeRepository->create($input);

        return $this->sendResponse($serviceTypes->toArray(), 'Service Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceTypes/{id}",
     *      summary="Display the specified ServiceType",
     *      tags={"ServiceType"},
     *      description="Get ServiceType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  ref="#/definitions/ServiceType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var ServiceType $serviceType */
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            return $this->sendError('Service Type not found');
        }

        return $this->sendResponse($serviceType->toArray(), 'Service Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateServiceTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/serviceTypes/{id}",
     *      summary="Update the specified ServiceType in storage",
     *      tags={"ServiceType"},
     *      description="Update ServiceType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ServiceType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ServiceType")
     *      ),
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
     *                  ref="#/definitions/ServiceType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateServiceTypeAPIRequest $request)
    {
        $input = $request->all();

        /** @var ServiceType $serviceType */
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            return $this->sendError('Service Type not found');
        }

        $serviceType = $this->serviceTypeRepository->update($input, $id);

        return $this->sendResponse($serviceType->toArray(), 'ServiceType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/serviceTypes/{id}",
     *      summary="Remove the specified ServiceType from storage",
     *      tags={"ServiceType"},
     *      description="Delete ServiceType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
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
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var ServiceType $serviceType */
        $serviceType = $this->serviceTypeRepository->findWithoutFail($id);

        if (empty($serviceType)) {
            return $this->sendError('Service Type not found');
        }

        $serviceType->delete();

        return $this->sendResponse($id, 'Service Type deleted successfully');
    }
}
