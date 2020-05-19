<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateViewingRequestAPIRequest;
use App\Http\Requests\API\UpdateViewingRequestAPIRequest;
use App\Models\ViewingRequest;
use App\Repositories\ViewingRequestRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Presenters\ViewingRequestIndexPresenter;
use App\Presenters\ViewingRequestPresenter;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ViewingRequestController
 * @package App\Http\Controllers\API
 */

class ViewingRequestAPIController extends AppBaseController
{
    /** @var  ViewingRequestRepository */
    private $viewingRequestRepository;

    public function __construct(ViewingRequestRepository $viewingRequestRepo)
    {
        $this->viewingRequestRepository = $viewingRequestRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/viewingRequests",
     *      summary="Get a listing of the ViewingRequests.",
     *      tags={"ViewingRequest"},
     *      description="Get all ViewingRequests",
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
     *                  @SWG\Items(ref="#/definitions/ViewingRequest")
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
        $this->viewingRequestRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->viewingRequestRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->viewingRequestRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->viewingRequestRepository->setPresenter(ViewingRequestIndexPresenter::class);
        $viewingRequests = $this->viewingRequestRepository->all();

        return $this->sendResponse($viewingRequests['data'], 'Viewing Requests retrieved successfully');
    }

    /**
     * @param CreateViewingRequestAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/viewingRequests",
     *      summary="Store a newly created ViewingRequest in storage",
     *      tags={"ViewingRequest"},
     *      description="Store ViewingRequest",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ViewingRequest that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ViewingRequest")
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
     *                  ref="#/definitions/ViewingRequest"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateViewingRequestAPIRequest $request)
    {
        $input = $request->all();
        $this->viewingRequestRepository->setPresenter(ViewingRequestPresenter::class);
        $viewingRequestCount = ViewingRequest::where('viewing_id', $input['viewing_id'])->count();
        if ($viewingRequestCount > 0) {
          return $this->sendError('Viewing Request already exists for this viewing');
        }
        $viewingRequests = $this->viewingRequestRepository->create($input);

        return $this->sendResponse($viewingRequests['data'], 'Viewing Request saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/viewingRequests/{id}",
     *      summary="Display the specified ViewingRequest",
     *      tags={"ViewingRequest"},
     *      description="Get ViewingRequest",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ViewingRequest",
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
     *                  ref="#/definitions/ViewingRequest"
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
        /** @var ViewingRequest $viewingRequest */
        $this->viewingRequestRepository->setPresenter(ViewingRequestPresenter::class);
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            return $this->sendError('Viewing Request not found');
        }

        return $this->sendResponse($viewingRequest['data'], 'Viewing Request retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateViewingRequestAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/viewingRequests/{id}",
     *      summary="Update the specified ViewingRequest in storage",
     *      tags={"ViewingRequest"},
     *      description="Update ViewingRequest",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ViewingRequest",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ViewingRequest that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ViewingRequest")
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
     *                  ref="#/definitions/ViewingRequest"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateViewingRequestAPIRequest $request)
    {
        $input = $request->all();

        /** @var ViewingRequest $viewingRequest */
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            return $this->sendError('Viewing Request not found');
        }
        if (isset($input['status']) && $viewingRequest->status != $input['status']) {
          $viewingRequest->{'transition'.$input['status']}();
        }
        $this->viewingRequestRepository->setPresenter(ViewingRequestPresenter::class);
        $viewingRequest = $this->viewingRequestRepository->update($input, $id);

        return $this->sendResponse($viewingRequest['data'], 'ViewingRequest updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/viewingRequests/{id}",
     *      summary="Remove the specified ViewingRequest from storage",
     *      tags={"ViewingRequest"},
     *      description="Delete ViewingRequest",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ViewingRequest",
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
        /** @var ViewingRequest $viewingRequest */
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            return $this->sendError('Viewing Request not found');
        }

        $viewingRequest->delete();

        return $this->sendResponse($id, 'Viewing Request deleted successfully');
    }
}
