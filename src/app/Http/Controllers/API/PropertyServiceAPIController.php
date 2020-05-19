<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePropertyServiceAPIRequest;
use App\Http\Requests\API\UpdatePropertyServiceAPIRequest;
use App\Models\PropertyService;
use App\Repositories\PropertyServiceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PropertyServiceController
 * @package App\Http\Controllers\API
 */

class PropertyServiceAPIController extends AppBaseController
{
    /** @var  PropertyServiceRepository */
    private $propertyServiceRepository;

    public function __construct(PropertyServiceRepository $propertyServiceRepo)
    {
        $this->propertyServiceRepository = $propertyServiceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/propertyServices",
     *      summary="Get a listing of the PropertyServices.",
     *      tags={"PropertyService"},
     *      description="Get all PropertyServices",
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
     *                  @SWG\Items(ref="#/definitions/PropertyService")
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
        $this->propertyServiceRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->propertyServiceRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->propertyServiceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $propertyServices = $this->propertyServiceRepository->all();

        return $this->sendResponse($propertyServices->toArray(), 'Property Services retrieved successfully');
    }

    /**
     * @param CreatePropertyServiceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/propertyServices",
     *      summary="Store a newly created PropertyService in storage",
     *      tags={"PropertyService"},
     *      description="Store PropertyService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PropertyService that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PropertyService")
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
     *                  ref="#/definitions/PropertyService"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePropertyServiceAPIRequest $request)
    {
        $input = $request->all();

        $propertyServices = $this->propertyServiceRepository->create($input);

        return $this->sendResponse($propertyServices->toArray(), 'Property Service saved successfully');
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/property_services/bulk",
     *      summary="Bulk insert",
     *      tags={"Viewing"},
     *      description="Store property services in bulk",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Pro services that should be stored",
     *          required=false,
     *          type="array",
     *          @SWG\Schema(ref="#/definitions/PropertyService")
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
     *                  ref="#/definitions/PropertyService"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function storeBulk(Request $request)
    {
        $input = $request->all();
        $propertyServices = [];
        foreach ($input as $row) {
          // if no service id then create new service
          if (!isset($row['service_id'])) {
            // crete new service
            if (!isset($row['title'])
                && !isset($row['type'])
                && !isset($row['description'])) {
              return $this->sendError('Please send title, type and description for creating new service', 400);
            }
            $service = Service::create([
              'title' => $row['title'],
              'type' => $row['type'],
              'description' => $row['description'],
            ]);
            $row['service_id'] = $service->id;
          }
          // update property service if id present
          if (isset($row['id'])) {
            PropertyService::updateOrCreate(
              [
                'id' => $row['id'],
              ],
              $row
            ); 
          } else {
            $propertyServices[] = $this->propertyServiceRepository->create($row);
          }
        }

        return $this->sendResponse($propertyServices, 'bulk saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/propertyServices/{id}",
     *      summary="Display the specified PropertyService",
     *      tags={"PropertyService"},
     *      description="Get PropertyService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PropertyService",
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
     *                  ref="#/definitions/PropertyService"
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
        /** @var PropertyService $propertyService */
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            return $this->sendError('Property Service not found');
        }

        return $this->sendResponse($propertyService->toArray(), 'Property Service retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePropertyServiceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/propertyServices/{id}",
     *      summary="Update the specified PropertyService in storage",
     *      tags={"PropertyService"},
     *      description="Update PropertyService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PropertyService",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PropertyService that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PropertyService")
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
     *                  ref="#/definitions/PropertyService"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePropertyServiceAPIRequest $request)
    {
        $input = $request->all();

        /** @var PropertyService $propertyService */
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            return $this->sendError('Property Service not found');
        }

        $propertyService = $this->propertyServiceRepository->update($input, $id);

        return $this->sendResponse($propertyService->toArray(), 'PropertyService updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/propertyServices/{id}",
     *      summary="Remove the specified PropertyService from storage",
     *      tags={"PropertyService"},
     *      description="Delete PropertyService",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PropertyService",
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
        /** @var PropertyService $propertyService */
        $propertyService = $this->propertyServiceRepository->findWithoutFail($id);

        if (empty($propertyService)) {
            return $this->sendError('Property Service not found');
        }

        $propertyService->delete();

        return $this->sendResponse($id, 'Property Service deleted successfully');
    }
}
