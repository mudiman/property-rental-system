<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateViewingAPIRequest;
use App\Http\Requests\API\UpdateViewingAPIRequest;
use App\Http\Requests\API\BulkViewingAPIRequest;
use App\Models\Viewing;
use App\Presenters\ViewingPresenter;
use App\Presenters\ViewingIndexPresenter;
use App\Repositories\ViewingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use Response;

/**
 * Class ViewingController
 * @package App\Http\Controllers\API
 */

class ViewingAPIController extends AppBaseController
{
    /** @var  ViewingRepository */
    private $viewingRepository;

    public function __construct(ViewingRepository $viewingRepo)
    {
        $this->viewingRepository = $viewingRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/viewings",
     *      summary="Get a listing of the Viewings.",
     *      tags={"Viewing"},
     *      description="Get all Viewings",
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
     *                  @SWG\Items(ref="#/definitions/Viewing")
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
        $this->viewingRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->viewingRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->viewingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->viewingRepository->setPresenter(ViewingIndexPresenter::class);
        $viewings = $this->viewingRepository->all();

        return $this->sendResponse($viewings['data'], 'Viewings retrieved successfully');
    }

    /**
     * @param CreateViewingAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/viewings",
     *      summary="Store a newly created Viewing in storage",
     *      tags={"Viewing"},
     *      description="Store Viewing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Viewing that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Viewing")
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
     *                  ref="#/definitions/Viewing"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateViewingAPIRequest $request)
    {
        $input = $request->all();
        $this->viewingRepository->setPresenter(ViewingPresenter::class);
        $viewingCount = Viewing::where('property_id', $input['property_id'])
            ->where('conducted_by', $input['conducted_by'])
            ->whereIn('status', [Viewing::AVAILABLE, Viewing::CONFIRM])
            ->where(function ($query) use ($input) {
                $query->orWhere(function ($query) use ($input) {
                  $query->where('start_datetime', '<=', $input['start_datetime'])
                      ->where('end_datetime', '>', $input['start_datetime']);
                });
                $query->orWhere(function ($query) use ($input) {
                  $query->where('start_datetime', '<', $input['end_datetime'])
                      ->where('end_datetime', '>=', $input['end_datetime']);
                });
            })->count();
        if ($viewingCount > 0) {
          return $this->sendError('Already made this request');
        }
        
        $viewings = $this->viewingRepository->create($input);

        return $this->sendResponse($viewings['data'], 'Viewing saved successfully');
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/viewings/bulk",
     *      summary="Bulk insert",
     *      tags={"Viewing"},
     *      description="Store Viewings in bulk",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Viewings that should be stored",
     *          required=false,
     *          type="array",
     *          @SWG\Schema(ref="#/definitions/Viewing")
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
     *                  ref="#/definitions/Viewing"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function storeBulk(BulkViewingAPIRequest $request)
    {
        $input = $request->all();
        $viewings = [];
        foreach ($input as $row) {
          $this->viewingRepository->setPresenter(ViewingPresenter::class);
          $viewings[] = $this->viewingRepository->create($row)['data'];
        }

        return $this->sendResponse($viewings, 'bulk saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/viewings/{id}",
     *      summary="Display the specified Viewing",
     *      tags={"Viewing"},
     *      description="Get Viewing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Viewing",
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
     *                  ref="#/definitions/Viewing"
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
        /** @var Viewing $viewing */
        $this->viewingRepository->setPresenter(ViewingPresenter::class);
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            return $this->sendError('Viewing not found');
        }

        return $this->sendResponse($viewing['data'], 'Viewing retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateViewingAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/viewings/{id}",
     *      summary="Update the specified Viewing in storage",
     *      tags={"Viewing"},
     *      description="Update Viewing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Viewing",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Viewing that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Viewing")
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
     *                  ref="#/definitions/Viewing"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateViewingAPIRequest $request)
    {
        $input = $request->all();

        /** @var Viewing $viewing */
        $this->viewingRepository->setPresenter(ViewingPresenter::class);
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            return $this->sendError('Viewing not found');
        }

        $viewing = $this->viewingRepository->update($input, $id);

        return $this->sendResponse($viewing['data'], 'Viewing updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/viewings/{id}",
     *      summary="Remove the specified Viewing from storage",
     *      tags={"Viewing"},
     *      description="Delete Viewing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Viewing",
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
        /** @var Viewing $viewing */
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            return $this->sendError('Viewing not found');
        }

        $viewing->delete();

        return $this->sendResponse($id, 'Viewing deleted successfully');
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/viewings/destroy_bulk",
     *      summary="Remove the specified Vlist of viewing from storage",
     *      tags={"Viewing"},
     *      description="Bulk Delete Viewing",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="viewing ids",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="ids",
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
    public function destroyBulk(Request $request)
    {
        $input = $request->all();
        $viewings = Viewing::whereIn('id', $input['ids'])->where('status', Viewing::AVAILABLE)->delete();
        return $this->sendResponse($viewings, 'Viewing deleted successfully');
    }
}
