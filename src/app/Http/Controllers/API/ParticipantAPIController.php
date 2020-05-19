<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateParticipantAPIRequest;
use App\Http\Requests\API\UpdateParticipantAPIRequest;
use App\Models\Participant;
use App\Repositories\ParticipantRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;

/**
 * Class ParticipantController
 * @package App\Http\Controllers\API
 */

class ParticipantAPIController extends AppBaseController
{
    /** @var  ParticipantRepository */
    private $participantRepository;

    public function __construct(ParticipantRepository $participantRepo)
    {
        $this->participantRepository = $participantRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/participants",
     *      summary="Get a listing of the Participants.",
     *      tags={"Participant"},
     *      description="Get all Participants",
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
     *                  @SWG\Items(ref="#/definitions/Participant")
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
        $this->participantRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->participantRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->participantRepository->pushCriteria(new LimitOffsetCriteria($request));
        $participants = $this->participantRepository->all();

        return $this->sendResponse($participants->toArray(), 'Participants retrieved successfully');
    }

    /**
     * @param CreateParticipantAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/participants",
     *      summary="Store a newly created Participant in storage",
     *      tags={"Participant"},
     *      description="Store Participant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Participant that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Participant")
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
     *                  ref="#/definitions/Participant"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateParticipantAPIRequest $request)
    {
        $input = $request->all();

        $participants = $this->participantRepository->create($input);

        return $this->sendResponse($participants->toArray(), 'Participant saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/participants/{id}",
     *      summary="Display the specified Participant",
     *      tags={"Participant"},
     *      description="Get Participant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Participant",
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
     *                  ref="#/definitions/Participant"
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
        /** @var Participant $participant */
        $participant = $this->participantRepository->findWithoutFail($id);

        if (empty($participant)) {
            return $this->sendError('Participant not found');
        }

        return $this->sendResponse($participant->toArray(), 'Participant retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateParticipantAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/participants/{id}",
     *      summary="Update the specified Participant in storage",
     *      tags={"Participant"},
     *      description="Update Participant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Participant",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Participant that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Participant")
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
     *                  ref="#/definitions/Participant"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateParticipantAPIRequest $request)
    {
        $input = $request->all();

        /** @var Participant $participant */
        $participant = $this->participantRepository->findWithoutFail($id);

        if (empty($participant)) {
            return $this->sendError('Participant not found');
        }

        $participant = $this->participantRepository->update($input, $id);

        return $this->sendResponse($participant->toArray(), 'Participant updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/participants/{id}",
     *      summary="Remove the specified Participant from storage",
     *      tags={"Participant"},
     *      description="Delete Participant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Participant",
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
        /** @var Participant $participant */
        $participant = $this->participantRepository->findWithoutFail($id);

        if (empty($participant)) {
            return $this->sendError('Participant not found');
        }

        $participant->delete();

        return $this->sendResponse($id, 'Participant deleted successfully');
    }
}
