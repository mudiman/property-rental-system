<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFeedbackAPIRequest;
use App\Http\Requests\API\UpdateFeedbackAPIRequest;
use App\Models\Feedback;
use App\Repositories\FeedbackRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class FeedbackController
 * @package App\Http\Controllers\API
 */

class FeedbackAPIController extends AppBaseController
{
    /** @var  FeedbackRepository */
    private $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepo)
    {
        $this->feedbackRepository = $feedbackRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/feedback",
     *      summary="Get a listing of the Feedback.",
     *      tags={"Feedback"},
     *      description="Get all Feedback",
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
     *                  @SWG\Items(ref="#/definitions/Feedback")
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
        $this->feedbackRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->feedbackRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->feedbackRepository->pushCriteria(new LimitOffsetCriteria($request));
        $feedback = $this->feedbackRepository->all();

        return $this->sendResponse($feedback->toArray(), 'Feedback retrieved successfully');
    }

    /**
     * @param CreateFeedbackAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/feedback",
     *      summary="Store a newly created Feedback in storage",
     *      tags={"Feedback"},
     *      description="Store Feedback",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Feedback that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Feedback")
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
     *                  ref="#/definitions/Feedback"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFeedbackAPIRequest $request)
    {
        $input = $request->all();

        $feedback = $this->feedbackRepository->create($input);

        return $this->sendResponse($feedback->toArray(), 'Feedback saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/feedback/{id}",
     *      summary="Display the specified Feedback",
     *      tags={"Feedback"},
     *      description="Get Feedback",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Feedback",
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
     *                  ref="#/definitions/Feedback"
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
        /** @var Feedback $feedback */
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            return $this->sendError('Feedback not found');
        }

        return $this->sendResponse($feedback->toArray(), 'Feedback retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFeedbackAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/feedback/{id}",
     *      summary="Update the specified Feedback in storage",
     *      tags={"Feedback"},
     *      description="Update Feedback",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Feedback",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Feedback that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Feedback")
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
     *                  ref="#/definitions/Feedback"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFeedbackAPIRequest $request)
    {
        $input = $request->all();

        /** @var Feedback $feedback */
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            return $this->sendError('Feedback not found');
        }

        $feedback = $this->feedbackRepository->update($input, $id);

        return $this->sendResponse($feedback->toArray(), 'Feedback updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/feedback/{id}",
     *      summary="Remove the specified Feedback from storage",
     *      tags={"Feedback"},
     *      description="Delete Feedback",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Feedback",
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
        /** @var Feedback $feedback */
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            return $this->sendError('Feedback not found');
        }

        $feedback->delete();

        return $this->sendResponse($id, 'Feedback deleted successfully');
    }
}
