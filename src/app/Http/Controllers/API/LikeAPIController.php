<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLikeAPIRequest;
use App\Http\Requests\API\UpdateLikeAPIRequest;
use App\Models\Like;
use App\Repositories\LikeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Presenters\LikePresenter;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;

/**
 * Class LikeController
 * @package App\Http\Controllers\API
 */

class LikeAPIController extends AppBaseController
{
    /** @var  LikeRepository */
    private $likeRepository;

    public function __construct(LikeRepository $likeRepo)
    {
        $this->likeRepository = $likeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/likes",
     *      summary="Get a listing of the Likes.",
     *      tags={"Like"},
     *      description="Get all Likes",
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
     *                  @SWG\Items(ref="#/definitions/Like")
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
        $this->likeRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->likeRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->likeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $likes = $this->likeRepository->all();

        return $this->sendResponse($likes->toArray(), 'Likes retrieved successfully');
    }

    /**
     * @param CreateLikeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/likes",
     *      summary="Store a newly created Like in storage",
     *      tags={"Like"},
     *      description="Store Like",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Like that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Like")
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
     *                  ref="#/definitions/Like"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateLikeAPIRequest $request)
    {
        $input = $request->all();
        $this->likeRepository->setPresenter(LikePresenter::class);
        $likes = $this->likeRepository->create($input);

        return $this->sendResponse($likes['data'], 'Like saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/likes/{id}",
     *      summary="Display the specified Like",
     *      tags={"Like"},
     *      description="Get Like",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Like",
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
     *                  ref="#/definitions/Like"
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
        /** @var Like $like */
        $this->likeRepository->setPresenter(LikePresenter::class);
        $like = $this->likeRepository->findWithoutFail($id);

        if (empty($like)) {
            return $this->sendError('Like not found');
        }

        return $this->sendResponse($like['data'], 'Like retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateLikeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/likes/{id}",
     *      summary="Update the specified Like in storage",
     *      tags={"Like"},
     *      description="Update Like",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Like",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Like that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Like")
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
     *                  ref="#/definitions/Like"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateLikeAPIRequest $request)
    {
        $input = $request->all();

        /** @var Like $like */
        $like = $this->likeRepository->findWithoutFail($id);

        if (empty($like)) {
            return $this->sendError('Like not found');
        }
        $this->likeRepository->setPresenter(LikePresenter::class);
        $like = $this->likeRepository->update($input, $id);

        return $this->sendResponse($like['data'], 'Like updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/likes/{id}",
     *      summary="Remove the specified Like from storage",
     *      tags={"Like"},
     *      description="Delete Like",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Like",
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
        /** @var Like $like */
        $like = $this->likeRepository->findWithoutFail($id);

        if (empty($like)) {
            return $this->sendError('Like not found');
        }

        $like->delete();

        return $this->sendResponse($id, 'Like deleted successfully');
    }
}
