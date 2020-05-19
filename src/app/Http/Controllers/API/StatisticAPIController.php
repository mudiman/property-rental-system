<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateStatisticAPIRequest;
use App\Http\Requests\API\UpdateStatisticAPIRequest;
use App\Models\Statistic;
use App\Repositories\StatisticRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class StatisticController
 * @package App\Http\Controllers\API
 */

class StatisticAPIController extends AppBaseController
{
    /** @var  StatisticRepository */
    private $statisticRepository;

    public function __construct(StatisticRepository $statisticRepo)
    {
        $this->statisticRepository = $statisticRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/statistics",
     *      summary="Get a listing of the Statistics.",
     *      tags={"Statistic"},
     *      description="Get all Statistics",
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
     *                  @SWG\Items(ref="#/definitions/Statistic")
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
        $this->statisticRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->statisticRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->statisticRepository->pushCriteria(new LimitOffsetCriteria($request));
        $statistics = $this->statisticRepository->all();

        return $this->sendResponse($statistics->toArray(), 'Statistics retrieved successfully');
    }

    /**
     * @param CreateStatisticAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/statistics",
     *      summary="Store a newly created Statistic in storage",
     *      tags={"Statistic"},
     *      description="Store Statistic",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Statistic that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Statistic")
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
     *                  ref="#/definitions/Statistic"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateStatisticAPIRequest $request)
    {
        $input = $request->all();

        $statistics = $this->statisticRepository->create($input);

        return $this->sendResponse($statistics->toArray(), 'Statistic saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/statistics/{id}",
     *      summary="Display the specified Statistic",
     *      tags={"Statistic"},
     *      description="Get Statistic",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Statistic",
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
     *                  ref="#/definitions/Statistic"
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
        /** @var Statistic $statistic */
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            return $this->sendError('Statistic not found');
        }

        return $this->sendResponse($statistic->toArray(), 'Statistic retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateStatisticAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/statistics/{id}",
     *      summary="Update the specified Statistic in storage",
     *      tags={"Statistic"},
     *      description="Update Statistic",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Statistic",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Statistic that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Statistic")
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
     *                  ref="#/definitions/Statistic"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateStatisticAPIRequest $request)
    {
        $input = $request->all();

        /** @var Statistic $statistic */
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            return $this->sendError('Statistic not found');
        }

        $statistic = $this->statisticRepository->update($input, $id);

        return $this->sendResponse($statistic->toArray(), 'Statistic updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/statistics/{id}",
     *      summary="Remove the specified Statistic from storage",
     *      tags={"Statistic"},
     *      description="Delete Statistic",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Statistic",
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
        /** @var Statistic $statistic */
        $statistic = $this->statisticRepository->findWithoutFail($id);

        if (empty($statistic)) {
            return $this->sendError('Statistic not found');
        }

        $statistic->delete();

        return $this->sendResponse($id, 'Statistic deleted successfully');
    }
}
