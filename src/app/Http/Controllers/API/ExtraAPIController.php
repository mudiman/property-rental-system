<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateExtraAPIRequest;
use App\Http\Requests\API\UpdateExtraAPIRequest;
use App\Models\Extra;
use App\Repositories\ExtraRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ExtraController
 * @package App\Http\Controllers\API
 */

class ExtraAPIController extends AppBaseController
{
    /** @var  ExtraRepository */
    private $extraRepository;

    public function __construct(ExtraRepository $extraRepo)
    {
        $this->extraRepository = $extraRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/extras",
     *      summary="Get a listing of the Extras.",
     *      tags={"Extra"},
     *      description="Get all Extras",
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
     *                  @SWG\Items(ref="#/definitions/Extra")
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
        $this->extraRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->extraRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->extraRepository->pushCriteria(new LimitOffsetCriteria($request));
        $extras = $this->extraRepository->all();

        return $this->sendResponse($extras->toArray(), 'Extras retrieved successfully');
    }

    /**
     * @param CreateExtraAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/extras",
     *      summary="Store a newly created Extra in storage",
     *      tags={"Extra"},
     *      description="Store Extra",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Extra that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Extra")
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
     *                  ref="#/definitions/Extra"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateExtraAPIRequest $request)
    {
        $input = $request->all();

        $extras = $this->extraRepository->create($input);

        return $this->sendResponse($extras->toArray(), 'Extra saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/extras/{id}",
     *      summary="Display the specified Extra",
     *      tags={"Extra"},
     *      description="Get Extra",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Extra",
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
     *                  ref="#/definitions/Extra"
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
        /** @var Extra $extra */
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            return $this->sendError('Extra not found');
        }

        return $this->sendResponse($extra->toArray(), 'Extra retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateExtraAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/extras/{id}",
     *      summary="Update the specified Extra in storage",
     *      tags={"Extra"},
     *      description="Update Extra",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Extra",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Extra that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Extra")
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
     *                  ref="#/definitions/Extra"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateExtraAPIRequest $request)
    {
        $input = $request->all();

        /** @var Extra $extra */
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            return $this->sendError('Extra not found');
        }

        $extra = $this->extraRepository->update($input, $id);

        return $this->sendResponse($extra->toArray(), 'Extra updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/extras/{id}",
     *      summary="Remove the specified Extra from storage",
     *      tags={"Extra"},
     *      description="Delete Extra",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Extra",
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
        /** @var Extra $extra */
        $extra = $this->extraRepository->findWithoutFail($id);

        if (empty($extra)) {
            return $this->sendError('Extra not found');
        }

        $extra->delete();

        return $this->sendResponse($id, 'Extra deleted successfully');
    }
}
