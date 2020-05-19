<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAgencyAPIRequest;
use App\Http\Requests\API\UpdateAgencyAPIRequest;
use App\Models\Agency;
use App\Repositories\AgencyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class AgencyController
 * @package App\Http\Controllers\API
 */

class AgencyAPIController extends AppBaseController
{
    /** @var  AgencyRepository */
    private $agencyRepository;

    public function __construct(AgencyRepository $agencyRepo)
    {
        $this->agencyRepository = $agencyRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/agencies",
     *      summary="Get a listing of the Agencies.",
     *      tags={"Agency"},
     *      description="Get all Agencies",
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
     *                  @SWG\Items(ref="#/definitions/Agency")
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
        $this->agencyRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->agencyRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->agencyRepository->pushCriteria(new LimitOffsetCriteria($request));
        $agencies = $this->agencyRepository->all();

        return $this->sendResponse($agencies->toArray(), 'Agencies retrieved successfully');
    }

    /**
     * @param CreateAgencyAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/agencies",
     *      summary="Store a newly created Agency in storage",
     *      tags={"Agency"},
     *      description="Store Agency",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Agency that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Agency")
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
     *                  ref="#/definitions/Agency"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAgencyAPIRequest $request)
    {
        $input = $request->all();

        $agencies = $this->agencyRepository->create($input);

        return $this->sendResponse($agencies->toArray(), 'Agency saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/agencies/{id}",
     *      summary="Display the specified Agency",
     *      tags={"Agency"},
     *      description="Get Agency",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Agency",
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
     *                  ref="#/definitions/Agency"
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
        /** @var Agency $agency */
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            return $this->sendError('Agency not found');
        }

        return $this->sendResponse($agency->toArray(), 'Agency retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAgencyAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/agencies/{id}",
     *      summary="Update the specified Agency in storage",
     *      tags={"Agency"},
     *      description="Update Agency",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Agency",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Agency that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Agency")
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
     *                  ref="#/definitions/Agency"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateAgencyAPIRequest $request)
    {
        $input = $request->all();

        /** @var Agency $agency */
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            return $this->sendError('Agency not found');
        }

        $agency = $this->agencyRepository->update($input, $id);

        return $this->sendResponse($agency->toArray(), 'Agency updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/agencies/{id}",
     *      summary="Remove the specified Agency from storage",
     *      tags={"Agency"},
     *      description="Delete Agency",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Agency",
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
        /** @var Agency $agency */
        $agency = $this->agencyRepository->findWithoutFail($id);

        if (empty($agency)) {
            return $this->sendError('Agency not found');
        }

        $agency->delete();

        return $this->sendResponse($id, 'Agency deleted successfully');
    }
}
