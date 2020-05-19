<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAgentAPIRequest;
use App\Http\Requests\API\UpdateAgentAPIRequest;
use App\Models\Agent;
use App\Repositories\AgentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class AgentController
 * @package App\Http\Controllers\API
 */

class AgentAPIController extends AppBaseController
{
    /** @var  AgentRepository */
    private $agentRepository;

    public function __construct(AgentRepository $agentRepo)
    {
        $this->agentRepository = $agentRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/agents",
     *      summary="Get a listing of the Agents.",
     *      tags={"Agent"},
     *      description="Get all Agents",
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
     *                  @SWG\Items(ref="#/definitions/Agent")
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
        $this->agentRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->agentRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->agentRepository->pushCriteria(new LimitOffsetCriteria($request));
        $agents = $this->agentRepository->all();

        return $this->sendResponse($agents->toArray(), 'Agents retrieved successfully');
    }

    /**
     * @param CreateAgentAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/agents",
     *      summary="Store a newly created Agent in storage",
     *      tags={"Agent"},
     *      description="Store Agent",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Agent that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Agent")
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
     *                  ref="#/definitions/Agent"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAgentAPIRequest $request)
    {
        $input = $request->all();

        $agents = $this->agentRepository->create($input);

        return $this->sendResponse($agents->toArray(), 'Agent saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/agents/{id}",
     *      summary="Display the specified Agent",
     *      tags={"Agent"},
     *      description="Get Agent",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Agent",
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
     *                  ref="#/definitions/Agent"
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
        /** @var Agent $agent */
        $agent = $this->agentRepository->findWithoutFail($id);

        if (empty($agent)) {
            return $this->sendError('Agent not found');
        }

        return $this->sendResponse($agent->toArray(), 'Agent retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAgentAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/agents/{id}",
     *      summary="Update the specified Agent in storage",
     *      tags={"Agent"},
     *      description="Update Agent",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Agent",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Agent that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Agent")
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
     *                  ref="#/definitions/Agent"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateAgentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Agent $agent */
        $agent = $this->agentRepository->findWithoutFail($id);

        if (empty($agent)) {
            return $this->sendError('Agent not found');
        }

        $agent = $this->agentRepository->update($input, $id);

        return $this->sendResponse($agent->toArray(), 'Agent updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/agents/{id}",
     *      summary="Remove the specified Agent from storage",
     *      tags={"Agent"},
     *      description="Delete Agent",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Agent",
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
        /** @var Agent $agent */
        $agent = $this->agentRepository->findWithoutFail($id);

        if (empty($agent)) {
            return $this->sendError('Agent not found');
        }

        $agent->delete();

        return $this->sendResponse($id, 'Agent deleted successfully');
    }
}
