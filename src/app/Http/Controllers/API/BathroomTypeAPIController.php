<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBathroomTypeAPIRequest;
use App\Http\Requests\API\UpdateBathroomTypeAPIRequest;
use App\Models\BathroomType;
use App\Repositories\BathroomTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class BathroomTypeController
 * @package App\Http\Controllers\API
 */

class BathroomTypeAPIController extends AppBaseController
{
    /** @var  BathroomTypeRepository */
    private $bathroomTypeRepository;

    public function __construct(BathroomTypeRepository $bathroomTypeRepo)
    {
        $this->bathroomTypeRepository = $bathroomTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/bathroomTypes",
     *      summary="Get a listing of the BathroomTypes.",
     *      tags={"BathroomType"},
     *      description="Get all BathroomTypes",
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
     *                  @SWG\Items(ref="#/definitions/BathroomType")
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
        $this->bathroomTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->bathroomTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->bathroomTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $bathroomTypes = $this->bathroomTypeRepository->all();

        return $this->sendResponse($bathroomTypes->toArray(), 'Bathroom Types retrieved successfully');
    }
}
