<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMessageAPIRequest;
use App\Http\Requests\API\UpdateMessageAPIRequest;
use App\Models\Message;
use App\Models\Thread;
use App\Repositories\MessageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;
use App\Models\Participant;

/**
 * Class MessageController
 * @package App\Http\Controllers\API
 */

class MessageAPIController extends AppBaseController
{
    /** @var  MessageRepository */
    private $messageRepository;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepository = $messageRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages",
     *      summary="Get a listing of the Messages.",
     *      tags={"Message"},
     *      description="Get all Messages",
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
     *                  @SWG\Items(ref="#/definitions/Message")
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
        $this->messageRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->messageRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->messageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $messages = $this->messageRepository->all();

        return $this->sendResponse($messages->toArray(), 'Messages retrieved successfully');
    }

    /**
     * @param CreateMessageAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/messages",
     *      summary="Store a newly created Message in storage",
     *      tags={"Message"},
     *      description="Store Message",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Message that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Message")
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
     *                  ref="#/definitions/Message"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateMessageAPIRequest $request)
    {
        $input = $request->all();
        if (empty($input['thread_id'])) {
          $thread = new Thread();
          $thread->title = 'thread'.$input['by_user'];
          $thread->save();
          $input['thread_id'] = $thread->id;
          $participant = new Participant();
          $participant->thread_id = $thread->id;
          $participant->user_id = $input['by_user'];
          $participant->save();
        }
        $messages = $this->messageRepository->create($input);

        return $this->sendResponse($messages->toArray(), 'Message saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages/{id}",
     *      summary="Display the specified Message",
     *      tags={"Message"},
     *      description="Get Message",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Message",
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
     *                  ref="#/definitions/Message"
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
        /** @var Message $message */
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        return $this->sendResponse($message->toArray(), 'Message retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateMessageAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/messages/{id}",
     *      summary="Update the specified Message in storage",
     *      tags={"Message"},
     *      description="Update Message",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Message",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Message that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Message")
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
     *                  ref="#/definitions/Message"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateMessageAPIRequest $request)
    {
        $input = $request->all();

        /** @var Message $message */
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        $message = $this->messageRepository->update($input, $id);

        return $this->sendResponse($message->toArray(), 'Message updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/messages/{id}",
     *      summary="Remove the specified Message from storage",
     *      tags={"Message"},
     *      description="Delete Message",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Message",
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
        /** @var Message $message */
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        $message->delete();

        return $this->sendResponse($id, 'Message deleted successfully');
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages/all",
     *      summary="Get a listing of the Messages.",
     *      tags={"Message"},
     *      description="Get all Messages",
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
     *                  @SWG\Items(ref="#/definitions/Message")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function all(Request $request)
    {
        $with = $request->get(config('repository.criteria.params.with', 'with'), null);
        
        $this->messageRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->messageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $messages = $this->messageRepository->all();
        
        $propertyIds = $this->messageRepository->getPropertiesIds($messages);
        $viewingRequestIds = $this->messageRepository->getViewingRequestIds($messages);
        $viewingPropertiesIds = $this->messageRepository->getViewingIds($messages);
        $likeIds = $this->messageRepository->getLikeIds($messages);
        
        $propertiesMap = $this->messageRepository->getProperties($propertyIds);
        $viewingMap = $this->messageRepository->getViewingRequests($viewingRequestIds);
        $propertiesMap += $this->messageRepository->getProperties($viewingPropertiesIds);
        $likeMap = $this->messageRepository->getLikes($likeIds);
        
        $this->messageRepository->attachRelation($messages, $propertiesMap, $viewingMap, $likeMap);
            
        return $this->sendResponse($messages->toArray(), 'Messages retrieved successfully');
    }
}
