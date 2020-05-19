<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateThreadAPIRequest;
use App\Http\Requests\API\UpdateThreadAPIRequest;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use App\Presenters\ThreadPresenter;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ThreadController
 * @package App\Http\Controllers\API
 */

class ThreadAPIController extends AppBaseController
{
    /** @var  ThreadRepository */
    private $threadRepository;

    public function __construct(ThreadRepository $threadRepo)
    {
        $this->threadRepository = $threadRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/threads",
     *      summary="Get a listing of the Threads.",
     *      tags={"Thread"},
     *      description="Get all Threads",
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
     *                  @SWG\Items(ref="#/definitions/Thread")
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
        $this->threadRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->threadRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->threadRepository->pushCriteria(new LimitOffsetCriteria($request));
        $threads = $this->threadRepository->all();

        return $this->sendResponse($threads->toArray(), 'Threads retrieved successfully');
    }

    /**
     * @param CreateThreadAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/threads",
     *      summary="Store a newly created Thread in storage",
     *      tags={"Thread"},
     *      description="Store Thread",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Thread that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Thread")
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
     *                  ref="#/definitions/Thread"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateThreadAPIRequest $request)
    {
        $input = $request->all();

        $threads = $this->threadRepository->create($input);

        return $this->sendResponse($threads->toArray(), 'Thread saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/threads/{id}",
     *      summary="Display the specified Thread",
     *      tags={"Thread"},
     *      description="Get Thread",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Thread",
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
     *                  ref="#/definitions/Thread"
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
        /** @var Thread $thread */
        $this->threadRepository->setPresenter(ThreadPresenter::class);
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            return $this->sendError('Thread not found');
        }

        return $this->sendResponse($thread['data'], 'Thread retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateThreadAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/threads/{id}",
     *      summary="Update the specified Thread in storage",
     *      tags={"Thread"},
     *      description="Update Thread",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Thread",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Thread that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Thread")
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
     *                  ref="#/definitions/Thread"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateThreadAPIRequest $request)
    {
        $input = $request->all();

        /** @var Thread $thread */
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            return $this->sendError('Thread not found');
        }
        
        $this->threadRepository->setPresenter(ThreadPresenter::class);
        $thread = $this->threadRepository->update($input, $id);

        return $this->sendResponse($thread['data'], 'Thread updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/threads/{id}",
     *      summary="Remove the specified Thread from storage",
     *      tags={"Thread"},
     *      description="Delete Thread",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Thread",
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
        /** @var Thread $thread */
        $thread = $this->threadRepository->findWithoutFail($id);

        if (empty($thread)) {
            return $this->sendError('Thread not found');
        }

        $thread->delete();

        return $this->sendResponse($id, 'Thread deleted successfully');
    }
    
    
    /**
     * @param int $id
     * @param UpdateThreadAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/participants/threads",
     *      summary="Get threads with specified list of participants create if now exist. Also if newParticipants is specified it adds them to existing thread",
     *      tags={"Thread"},
     *      description="Update Thread",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Specific participants with user_id list [1,2,3]",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="participants",
     *                  type="string"
     *              ),
     *             @SWG\Property(
     *                  property="newParticipants",
     *                  type="string"
     *              )
     *          )
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
     *                  ref="#/definitions/Thread"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function participantsThread(Request $request)
    {
      $input = $request->all();
      $newParticipants = isset($input['newParticipants']) ? $input['newParticipants']: null;
      
      if (!isset($input['participants']) || !is_array($input['participants'])) {
          return $this->sendError('participants id array list missing');
      }
      if (isset($input['newParticipants']) && !is_array($input['newParticipants'])) {
          return $this->sendError('newParticipants is not array list');
      }
      
      $threadId = $this->threadRepository->participantsThread($input['participants'], $newParticipants);
      
      $this->threadRepository->setPresenter(ThreadPresenter::class);
      $thread = $this->threadRepository->findWithoutFail($threadId);
      
      
      return $this->sendResponse($thread['data'], 'Threads retrieved successfully');
    }
}
