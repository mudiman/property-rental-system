<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLettingTypeAPIRequest;
use App\Http\Requests\API\UpdateLettingTypeAPIRequest;
use App\Models\LettingType;
use App\Repositories\LettingTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class LettingTypeController
 * @package App\Http\Controllers\API
 */

class LettingTypeAPIController extends AppBaseController
{
    /** @var  LettingTypeRepository */
    private $lettingTypeRepository;

    public function __construct(LettingTypeRepository $lettingTypeRepo)
    {
        $this->lettingTypeRepository = $lettingTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/lettingTypes",
     *      summary="Get a listing of the LettingTypes.",
     *      tags={"LettingType"},
     *      description="Get all LettingTypes",
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
     *                  @SWG\Items(ref="#/definitions/LettingType")
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
        $this->lettingTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->lettingTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->lettingTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $lettingTypes = $this->lettingTypeRepository->all();

        return $this->sendResponse($lettingTypes->toArray(), 'Letting Types retrieved successfully');
    }
}
