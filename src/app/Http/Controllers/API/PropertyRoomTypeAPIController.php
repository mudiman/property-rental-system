<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePropertyRoomTypeAPIRequest;
use App\Http\Requests\API\UpdatePropertyRoomTypeAPIRequest;
use App\Models\PropertyRoomType;
use App\Repositories\PropertyRoomTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class PropertyRoomTypeController
 * @package App\Http\Controllers\API
 */

class PropertyRoomTypeAPIController extends AppBaseController
{
    /** @var  PropertyRoomTypeRepository */
    private $propertyRoomTypeRepository;

    public function __construct(PropertyRoomTypeRepository $propertyRoomTypeRepo)
    {
        $this->propertyRoomTypeRepository = $propertyRoomTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/property_room_types",
     *      summary="Get a listing of the PropertyRoomTypes.",
     *      tags={"PropertyRoomType"},
     *      description="Get all PropertyRoomTypes",
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
     *                  @SWG\Items(ref="#/definitions/PropertyRoomType")
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
        $this->propertyRoomTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->propertyRoomTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->propertyRoomTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $propertyRoomTypes = $this->propertyRoomTypeRepository->all();

        return $this->sendResponse($propertyRoomTypes->toArray(), 'Property Room Types retrieved successfully');
    }
}
