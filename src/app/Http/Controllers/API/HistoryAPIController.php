<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHistoryAPIRequest;
use App\Http\Requests\API\UpdateHistoryAPIRequest;
use App\Models\History;
use App\Repositories\HistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class HistoryController
 * @package App\Http\Controllers\API
 */

class HistoryAPIController extends AppBaseController
{
    /** @var  HistoryRepository */
    private $historyRepository;

    public function __construct(HistoryRepository $historyRepo)
    {
        $this->historyRepository = $historyRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/histories",
     *      summary="Get a listing of the Histories.",
     *      tags={"History"},
     *      description="Get all Histories",
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
     *                  @SWG\Items(ref="#/definitions/History")
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
        $this->historyRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->historyRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->historyRepository->pushCriteria(new LimitOffsetCriteria($request));
        $histories = $this->historyRepository->all();

        return $this->sendResponse($histories->toArray(), 'Histories retrieved successfully');
    }

    /**
     * @param CreateHistoryAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/histories",
     *      summary="Store a newly created History in storage",
     *      tags={"History"},
     *      description="Store History",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="History that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/History")
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
     *                  ref="#/definitions/History"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateHistoryAPIRequest $request)
    {
        $input = $request->all();

        $histories = $this->historyRepository->create($input);

        return $this->sendResponse($histories->toArray(), 'History saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/histories/{id}",
     *      summary="Display the specified History",
     *      tags={"History"},
     *      description="Get History",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of History",
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
     *                  ref="#/definitions/History"
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
        /** @var History $history */
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            return $this->sendError('History not found');
        }

        return $this->sendResponse($history->toArray(), 'History retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateHistoryAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/histories/{id}",
     *      summary="Update the specified History in storage",
     *      tags={"History"},
     *      description="Update History",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of History",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="History that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/History")
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
     *                  ref="#/definitions/History"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var History $history */
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            return $this->sendError('History not found');
        }

        $history = $this->historyRepository->update($input, $id);

        return $this->sendResponse($history->toArray(), 'History updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/histories/{id}",
     *      summary="Remove the specified History from storage",
     *      tags={"History"},
     *      description="Delete History",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of History",
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
        /** @var History $history */
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            return $this->sendError('History not found');
        }

        $history->delete();

        return $this->sendResponse($id, 'History deleted successfully');
    }
}
