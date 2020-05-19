<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePayinAPIRequest;
use App\Http\Requests\API\UpdatePayinAPIRequest;
use App\Models\Payin;
use App\Repositories\PayinRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Presenters\PayinPresenter;
use App\Presenters\PayinIndexPresenter;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;

/**
 * Class PayinController
 * @package App\Http\Controllers\API
 */

class PayinAPIController extends AppBaseController
{
    /** @var  PayinRepository */
    private $payinRepository;

    public function __construct(PayinRepository $payinRepo)
    {
        $this->payinRepository = $payinRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/payins",
     *      summary="Get a listing of the Payins.",
     *      tags={"Payin"},
     *      description="Get all Payins",
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
     *                  @SWG\Items(ref="#/definitions/Payin")
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
        $this->payinRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->payinRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->payinRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->payinRepository->setPresenter(PayinIndexPresenter::class);
        $payins = $this->payinRepository->all();

        return $this->sendResponse($payins['data'], 'Payins retrieved successfully');
    }

    /**
     * @param CreatePayinAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/payins",
     *      summary="Store a newly created Payin in storage",
     *      tags={"Payin"},
     *      description="Store Payin",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Payin that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Payin")
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
     *                  ref="#/definitions/Payin"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePayinAPIRequest $request)
    {
        $input = $request->all();
        $input['ip'] = $request->ip();
        $inputData =  $input
            + Auth::user()->toArray();
        
        $this->payinRepository->setPresenter(PayinPresenter::class);
        $payins = $this->payinRepository->create($inputData);
        
        return $this->sendResponse($payins['data'], 'Payin saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/payins/{id}",
     *      summary="Display the specified Payin",
     *      tags={"Payin"},
     *      description="Get Payin",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payin",
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
     *                  ref="#/definitions/Payin"
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
        /** @var Payin $payin */
        $this->payinRepository->setPresenter(PayinPresenter::class);
        $payin = $this->payinRepository->findWithoutFail($id);

        if (empty($payin)) {
            return $this->sendError('Payin not found');
        }

        return $this->sendResponse($payin['data'], 'Payin retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePayinAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/payins/{id}",
     *      summary="Update the specified Payin in storage",
     *      tags={"Payin"},
     *      description="Update Payin",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payin",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Payin that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Payin")
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
     *                  ref="#/definitions/Payin"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePayinAPIRequest $request)
    {
        $input = $request->all();

        /** @var Payin $payin */
        $payin = $this->payinRepository->findWithoutFail($id);

        if (empty($payin)) {
            return $this->sendError('Payin not found');
        }
        $this->payinRepository->setPresenter(PayinPresenter::class);
        $payin = $this->payinRepository->update($input, $id);

        return $this->sendResponse($payin['data'], 'Payin updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/payins/{id}",
     *      summary="Remove the specified Payin from storage",
     *      tags={"Payin"},
     *      description="Delete Payin",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payin",
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
        /** @var Payin $payin */
        $payin = $this->payinRepository->findWithoutFail($id);

        if (empty($payin)) {
            return $this->sendError('Payin not found');
        }

        $payin->delete();

        return $this->sendResponse($id, 'Payin deleted successfully');
    }
}
