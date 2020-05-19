<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateScoreAPIRequest;
use App\Http\Requests\API\UpdateScoreAPIRequest;
use App\Models\Score;
use App\Repositories\ScoreRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ScoreController
 * @package App\Http\Controllers\API
 */

class ScoreAPIController extends AppBaseController
{
    /** @var  ScoreRepository */
    private $scoreRepository;

    public function __construct(ScoreRepository $scoreRepo)
    {
        $this->scoreRepository = $scoreRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/scores",
     *      summary="Get a listing of the Scores.",
     *      tags={"Score"},
     *      description="Get all Scores",
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
     *                  @SWG\Items(ref="#/definitions/Score")
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
        $this->scoreRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->scoreRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->scoreRepository->pushCriteria(new LimitOffsetCriteria($request));
        $scores = $this->scoreRepository->all();

        return $this->sendResponse($scores->toArray(), 'Scores retrieved successfully');
    }

    /**
     * @param CreateScoreAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/scores",
     *      summary="Store a newly created Score in storage",
     *      tags={"Score"},
     *      description="Store Score",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Score that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Score")
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
     *                  ref="#/definitions/Score"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateScoreAPIRequest $request)
    {
        $input = $request->all();

        $scores = $this->scoreRepository->create($input);

        return $this->sendResponse($scores->toArray(), 'Score saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/scores/{id}",
     *      summary="Display the specified Score",
     *      tags={"Score"},
     *      description="Get Score",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Score",
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
     *                  ref="#/definitions/Score"
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
        /** @var Score $score */
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            return $this->sendError('Score not found');
        }

        return $this->sendResponse($score->toArray(), 'Score retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateScoreAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/scores/{id}",
     *      summary="Update the specified Score in storage",
     *      tags={"Score"},
     *      description="Update Score",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Score",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Score that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Score")
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
     *                  ref="#/definitions/Score"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateScoreAPIRequest $request)
    {
        $input = $request->all();

        /** @var Score $score */
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            return $this->sendError('Score not found');
        }

        $score = $this->scoreRepository->update($input, $id);

        return $this->sendResponse($score->toArray(), 'Score updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/scores/{id}",
     *      summary="Remove the specified Score from storage",
     *      tags={"Score"},
     *      description="Delete Score",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Score",
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
        /** @var Score $score */
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            return $this->sendError('Score not found');
        }

        $score->delete();

        return $this->sendResponse($id, 'Score deleted successfully');
    }
}
