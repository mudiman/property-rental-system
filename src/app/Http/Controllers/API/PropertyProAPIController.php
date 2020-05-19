<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePropertyProAPIRequest;
use App\Http\Requests\API\UpdatePropertyProAPIRequest;
use App\Models\PropertyPro;
use App\Repositories\PropertyProRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class PropertyProController
 * @package App\Http\Controllers\API
 */

class PropertyProAPIController extends AppBaseController
{
    /** @var  PropertyProRepository */
    private $propertyProRepository;

    public function __construct(PropertyProRepository $propertyProRepo)
    {
        $this->propertyProRepository = $propertyProRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/propertyPros",
     *      summary="Get a listing of the PropertyPros.",
     *      tags={"PropertyPro"},
     *      description="Get all PropertyPros",
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
     *                  @SWG\Items(ref="#/definitions/PropertyPro")
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
        $this->propertyProRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->propertyProRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->propertyProRepository->pushCriteria(new LimitOffsetCriteria($request));
        $propertyPros = $this->propertyProRepository->all();

        return $this->sendResponse($propertyPros->toArray(), 'Property Pros retrieved successfully');
    }

    /**
     * @param CreatePropertyProAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/propertyPros",
     *      summary="Store a newly created PropertyPro in storage",
     *      tags={"PropertyPro"},
     *      description="Store PropertyPro",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PropertyPro that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PropertyPro")
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
     *                  ref="#/definitions/PropertyPro"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePropertyProAPIRequest $request)
    {
        $input = $request->all();
        if (!isset($input['thread'])) {
          $input['thread'] = uniqid();
        }
        $propertyPros = $this->propertyProRepository->create($input);

        return $this->sendResponse($propertyPros->toArray(), 'Property Pro saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/propertyPros/{id}",
     *      summary="Display the specified PropertyPro",
     *      tags={"PropertyPro"},
     *      description="Get PropertyPro",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PropertyPro",
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
     *                  ref="#/definitions/PropertyPro"
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
        /** @var PropertyPro $propertyPro */
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            return $this->sendError('Property Pro not found');
        }

        return $this->sendResponse($propertyPro->toArray(), 'Property Pro retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePropertyProAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/propertyPros/{id}",
     *      summary="Update the specified PropertyPro in storage",
     *      tags={"PropertyPro"},
     *      description="Update PropertyPro",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PropertyPro",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PropertyPro that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PropertyPro")
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
     *                  ref="#/definitions/PropertyPro"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePropertyProAPIRequest $request)
    {
        $input = $request->all();

        /** @var PropertyPro $propertyPro */
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            return $this->sendError('Property Pro not found');
        }

        $propertyPro = $this->propertyProRepository->update($input, $id);

        return $this->sendResponse($propertyPro->toArray(), 'PropertyPro updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/propertyPros/{id}",
     *      summary="Remove the specified PropertyPro from storage",
     *      tags={"PropertyPro"},
     *      description="Delete PropertyPro",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PropertyPro",
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
        /** @var PropertyPro $propertyPro */
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            return $this->sendError('Property Pro not found');
        }

        $propertyPro->delete();

        return $this->sendResponse($id, 'Property Pro deleted successfully');
    }
}
