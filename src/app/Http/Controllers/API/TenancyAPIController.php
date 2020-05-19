<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTenancyAPIRequest;
use App\Http\Requests\API\UpdateTenancyAPIRequest;
use App\Http\Requests\API\TenancyPayAPIRequest;
use App\Presenters\TenancyPresenter;
use App\Presenters\TenancyIndexPresenter;
use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use App\Criteria\LocationRequestCriteria;
use Response;
use App\Models\Transaction;

/**
 * Class TenancyController
 * @package App\Http\Controllers\API
 */

class TenancyAPIController extends AppBaseController
{
    /** @var  TenancyRepository */
    private $tenancyRepository;

    public function __construct(TenancyRepository $tenancyRepo)
    {
        $this->tenancyRepository = $tenancyRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/tenancies",
     *      summary="Get a listing of the Tenancies.",
     *      tags={"Tenancy"},
     *      description="Get all Tenancies",
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
     *                  @SWG\Items(ref="#/definitions/Tenancy")
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
        $this->tenancyRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->tenancyRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->tenancyRepository->pushCriteria(new LocationRequestCriteria($request));
        $this->tenancyRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->tenancyRepository->setPresenter(TenancyIndexPresenter::class);
        $tenancies = $this->tenancyRepository->all();

        return $this->sendResponse($tenancies['data'], 'Tenancies retrieved successfully');
    }

    /**
     * @param CreateTenancyAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/tenancies",
     *      summary="Store a newly created Tenancy in storage",
     *      tags={"Tenancy"},
     *      description="Store Tenancy",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Tenancy that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Tenancy")
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
     *                  ref="#/definitions/Tenancy"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateTenancyAPIRequest $request)
    {
        $input = $request->all();
        if (!isset($input['thread'])) {
          $input['thread'] = uniqid();
        }
        $this->tenancyRepository->setPresenter(TenancyPresenter::class);
        $tenancies = $this->tenancyRepository->create($input);

        return $this->sendResponse($tenancies['data'], 'Tenancy saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/tenancies/{id}",
     *      summary="Display the specified Tenancy",
     *      tags={"Tenancy"},
     *      description="Get Tenancy",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Tenancy",
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
     *                  ref="#/definitions/Tenancy"
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
        /** @var Tenancy $tenancy */
        $this->tenancyRepository->setPresenter(TenancyPresenter::class);
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        if (empty($tenancy)) {
            return $this->sendError('Tenancy not found');
        }

        return $this->sendResponse($tenancy['data'], 'Tenancy retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateTenancyAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/tenancies/{id}",
     *      summary="Update the specified Tenancy in storage",
     *      tags={"Tenancy"},
     *      description="Update Tenancy",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Tenancy",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Tenancy that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Tenancy")
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
     *                  ref="#/definitions/Tenancy"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateTenancyAPIRequest $request)
    {
        $input = $request->all();

        /** @var Tenancy $tenancy */
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        if (empty($tenancy)) {
            return $this->sendError('Tenancy not found');
        }
        
        if (isset($input['status']) && $tenancy->status != $input['status']) {
          $tenancy->{'transition'.$input['status']}();
        }
        $this->tenancyRepository->setPresenter(TenancyPresenter::class);
        $tenancy = $this->tenancyRepository->update($input, $id);

        return $this->sendResponse($tenancy['data'], 'Tenancy updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/tenancies/{id}",
     *      summary="Remove the specified Tenancy from storage",
     *      tags={"Tenancy"},
     *      description="Delete Tenancy",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Tenancy",
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
        /** @var Tenancy $tenancy */
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        if (empty($tenancy)) {
            return $this->sendError('Tenancy not found');
        }

        $tenancy->delete();

        return $this->sendResponse($id, 'Tenancy deleted successfully');
    }
    
    /**
     * @param TenancyPayAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/tenancies/{id}/pay",
     *      summary="take monthly pay",
     *      tags={"Tenancy"},
     *      description="pay",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of tenancy",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="transaction id to pay for",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="transaction_id",
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
     *                  ref="#/definitions/Tenancy"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function pay($id, TenancyPayAPIRequest $request)
    {
        $input = $request->all();
        /** @var Offer $offer */
        $tenancy = $this->tenancyRepository->findWithoutFail($id);
        if (empty($tenancy)) {
            return $this->sendError('Tenancy not found');
        }
        if (empty($tenancy->payout_id)) {
            return $this->sendError('No default payment method set', 400);
        }
        if ($tenancy->status == Tenancy::PRESIGN) {
            return $this->sendError('Tenancy aggreement needs to be binded', 400);
        }
        
        $transactions = [];
        $transactionRepository = \App::make(TransactionRepository::class);
        if (isset($input['transaction_id'])) {
          $transaction = $transactionRepository->findWithoutFail($input['transaction_id']);
          if (empty($transaction)) {
              return $this->sendError('Transaction not found');
          }
          if ($transaction->status == Transaction::STATUS_DONE) {
            return $this->sendError('Already paid this transaction', 400);
          }
          $transactions[] = $transaction;
        } else {
          $transactions = $tenancy->transactionsDue()->orderBy('transactions.created_at')->get();
          if ($transactions->isEmpty()) {
            return $this->sendError('No transaction left to pay', 400);
          }
        }
        // payout all transaction
        foreach ($transactions as $transaction) {
          $updatedTransaction = $transactionRepository->payoutTransaction($tenancy->payout, $transaction);
          if ($updatedTransaction->status == Transaction::STATUS_FAILED) {
            return $this->sendError(sprintf('Payment failed because (%s)', $transaction->payment_error_message));
          }
          if ($transaction->status == Transaction::STATUS_DONE) {
            $tenancy->due_date = $transaction->due_date->addMonths(1);
            $tenancy->due_amount -= $transaction->amount;
            $tenancy->save();
          }
        }
        $this->tenancyRepository->setPresenter(TenancyPresenter::class);
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        return $this->sendResponse($tenancy['data'], 'Tenancy rent paid successfully');
    }
}
