<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePayoutAPIRequest;
use App\Http\Requests\API\UpdatePayoutAPIRequest;
use App\Models\Payout;
use App\Repositories\PayoutRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Presenters\PayoutPresenter;
use App\Presenters\PayoutIndexPresenter;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;

/**
 * Class PayoutController
 * @package App\Http\Controllers\API
 */

class PayoutAPIController extends AppBaseController
{
    /** @var  PayoutRepository */
    private $payoutRepository;

    public function __construct(PayoutRepository $payoutRepo)
    {
        $this->payoutRepository = $payoutRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/payouts",
     *      summary="Get a listing of the Payouts.",
     *      tags={"Payout"},
     *      description="Get all Payouts",
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
     *                  @SWG\Items(ref="#/definitions/Payout")
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
        $this->payoutRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->payoutRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->payoutRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->payoutRepository->setPresenter(PayoutIndexPresenter::class);

        $payouts = $this->payoutRepository->all();

        return $this->sendResponse($payouts['data'], 'Payouts retrieved successfully');
    }

    /**
     * @param CreatePayoutAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/payouts",
     *      summary="Store a newly created Payout in storage",
     *      tags={"Payout"},
     *      description="Store Payout",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Payout that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Payout")
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
     *                  ref="#/definitions/Payout"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePayoutAPIRequest $request)
    {
        $input = $request->all();
        if (!isset($input['ip'])) {
          $input['ip'] = $request->ip();
        }
        $this->payoutRepository->setPresenter(PayoutPresenter::class);
        $payouts = $this->payoutRepository->create($input);

        return $this->sendResponse($payouts['data'], 'Payout saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/payouts/{id}",
     *      summary="Display the specified Payout",
     *      tags={"Payout"},
     *      description="Get Payout",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payout",
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
     *                  ref="#/definitions/Payout"
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
        /** @var Payout $payout */
        $this->payoutRepository->setPresenter(PayoutPresenter::class);
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            return $this->sendError('Payout not found');
        }

        return $this->sendResponse($payout['data'], 'Payout retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePayoutAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/payouts/{id}",
     *      summary="Update the specified Payout in storage",
     *      tags={"Payout"},
     *      description="Update Payout",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payout",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Payout that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Payout")
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
     *                  ref="#/definitions/Payout"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePayoutAPIRequest $request)
    {
        $input = $request->all();

        /** @var Payout $payout */
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            return $this->sendError('Payout not found');
        }
        $this->payoutRepository->setPresenter(PayoutPresenter::class);
        $payout = $this->payoutRepository->update($input, $id);

        return $this->sendResponse($payout['data'], 'Payout updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/payouts/{id}",
     *      summary="Remove the specified Payout from storage",
     *      tags={"Payout"},
     *      description="Delete Payout",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payout",
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
        /** @var Payout $payout */
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            return $this->sendError('Payout not found');
        }

        $payout->delete();

        return $this->sendResponse($id, 'Payout deleted successfully');
    }
}
