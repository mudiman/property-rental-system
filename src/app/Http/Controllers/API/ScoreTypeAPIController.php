<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateScoreTypeAPIRequest;
use App\Http\Requests\API\UpdateScoreTypeAPIRequest;
use App\Models\ScoreType;
use App\Repositories\ScoreTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ScoreTypeController
 * @package App\Http\Controllers\API
 */

class ScoreTypeAPIController extends AppBaseController
{
    /** @var  ScoreTypeRepository */
    private $scoreTypeRepository;

    public function __construct(ScoreTypeRepository $scoreTypeRepo)
    {
        $this->scoreTypeRepository = $scoreTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/scoreTypes",
     *      summary="Get a listing of the ScoreTypes.",
     *      tags={"ScoreType"},
     *      description="Get all ScoreTypes",
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
     *                  @SWG\Items(ref="#/definitions/ScoreType")
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
        $this->scoreTypeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->scoreTypeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->scoreTypeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $scoreTypes = $this->scoreTypeRepository->all();

        return $this->sendResponse($scoreTypes->toArray(), 'Score Types retrieved successfully');
    }

    /**
     * @param CreateScoreTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/scoreTypes",
     *      summary="Store a newly created ScoreType in storage",
     *      tags={"ScoreType"},
     *      description="Store ScoreType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ScoreType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ScoreType")
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
     *                  ref="#/definitions/ScoreType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateScoreTypeAPIRequest $request)
    {
        $input = $request->all();

        $scoreTypes = $this->scoreTypeRepository->create($input);

        return $this->sendResponse($scoreTypes->toArray(), 'Score Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/scoreTypes/{id}",
     *      summary="Display the specified ScoreType",
     *      tags={"ScoreType"},
     *      description="Get ScoreType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ScoreType",
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
     *                  ref="#/definitions/ScoreType"
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
        /** @var ScoreType $scoreType */
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            return $this->sendError('Score Type not found');
        }

        return $this->sendResponse($scoreType->toArray(), 'Score Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateScoreTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/scoreTypes/{id}",
     *      summary="Update the specified ScoreType in storage",
     *      tags={"ScoreType"},
     *      description="Update ScoreType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ScoreType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ScoreType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ScoreType")
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
     *                  ref="#/definitions/ScoreType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateScoreTypeAPIRequest $request)
    {
        $input = $request->all();

        /** @var ScoreType $scoreType */
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            return $this->sendError('Score Type not found');
        }

        $scoreType = $this->scoreTypeRepository->update($input, $id);

        return $this->sendResponse($scoreType->toArray(), 'ScoreType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/scoreTypes/{id}",
     *      summary="Remove the specified ScoreType from storage",
     *      tags={"ScoreType"},
     *      description="Delete ScoreType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ScoreType",
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
        /** @var ScoreType $scoreType */
        $scoreType = $this->scoreTypeRepository->findWithoutFail($id);

        if (empty($scoreType)) {
            return $this->sendError('Score Type not found');
        }

        $scoreType->delete();

        return $this->sendResponse($id, 'Score Type deleted successfully');
    }
}
