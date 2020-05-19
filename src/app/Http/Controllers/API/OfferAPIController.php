<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOfferAPIRequest;
use App\Http\Requests\API\UpdateOfferAPIRequest;
use App\Http\Requests\API\OfferSecurityDepositAPIRequest;
use App\Presenters\OfferPresenter;
use App\Presenters\OfferIndexPresenter;
use App\Models\Offer;
use App\Repositories\OfferRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use App\Criteria\LocationRequestCriteria;
use Response;
use App\Repositories\TransactionRepository;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Tenancy;

/**
 * Class OfferController
 * @package App\Http\Controllers\API
 */

class OfferAPIController extends AppBaseController
{
    /** @var  OfferRepository */
    private $offerRepository;

    public function __construct(OfferRepository $offerRepo)
    {
        $this->offerRepository = $offerRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/offers",
     *      summary="Get a listing of the Offers.",
     *      tags={"Offer"},
     *      description="Get all Offers",
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
     *                  @SWG\Items(ref="#/definitions/Offer")
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
        $this->offerRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->offerRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->offerRepository->pushCriteria(new LocationRequestCriteria($request));
        $this->offerRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->offerRepository->setPresenter(OfferIndexPresenter::class);
        $offers = $this->offerRepository->all();

        return $this->sendResponse($offers['data'], 'Offers retrieved successfully');
    }

    /**
     * @param CreateOfferAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/offers",
     *      summary="Store a newly created Offer in storage",
     *      tags={"Offer"},
     *      description="Store Offer",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Offer that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Offer")
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
     *                  ref="#/definitions/Offer"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateOfferAPIRequest $request)
    {
        $input = $request->all();
        if (!isset($input['thread'])) {
          $input['thread'] = uniqid();
        }
        $tenanciesCount = Tenancy::where('property_id', $input['property_id'])
            ->whereIn('status', [Tenancy::START, Tenancy::SIGNING_COMPLETE, Tenancy::ROLLING, Tenancy::NOTICE, Tenancy::PRE_NOTICE])
            ->where(function ($query) use ($input) {
                $query->orWhere(function ($query) use ($input) {
                  $query->where('checkin', '<=', $input['checkin'])
                      ->where('actual_checkout', '>=', $input['checkin']);
                });
                $query->orWhere(function ($query) use ($input) {
                  $query->where('checkin', '<=', $input['checkout'])
                      ->where('actual_checkout', '>=', $input['checkout']);
                });
            })->count();
        if ($tenanciesCount > 0) {
          return $this->sendError('There is existing tenancies in specified duration');
        }
        $this->offerRepository->setPresenter(OfferPresenter::class);
        $offers = $this->offerRepository->create($input);

        return $this->sendResponse($offers['data'], 'Offer saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/offers/{id}",
     *      summary="Display the specified Offer",
     *      tags={"Offer"},
     *      description="Get Offer",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Offer",
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
     *                  ref="#/definitions/Offer"
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
        /** @var Offer $offer */
        $this->offerRepository->setPresenter(OfferPresenter::class);
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            return $this->sendError('Offer not found');
        }

        return $this->sendResponse($offer['data'], 'Offer retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateOfferAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/offers/{id}",
     *      summary="Update the specified Offer in storage",
     *      tags={"Offer"},
     *      description="Update Offer",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Offer",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Offer that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Offer")
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
     *                  ref="#/definitions/Offer"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateOfferAPIRequest $request)
    {
        $input = $request->all();

        /** @var Offer $offer */
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            return $this->sendError('Offer not found');
        }
        
        if (isset($input['status']) && $offer->status != $input['status']) {
          $offer->{'transition'.$input['status']}();
        }
        $this->offerRepository->setPresenter(OfferPresenter::class);
        $offer = $this->offerRepository->update($input, $id);

        return $this->sendResponse($offer['data'], 'Offer updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/offers/{id}",
     *      summary="Remove the specified Offer from storage",
     *      tags={"Offer"},
     *      description="Delete Offer",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Offer",
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
        /** @var Offer $offer */
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            return $this->sendError('Offer not found');
        }

        $offer->delete();

        return $this->sendResponse($id, 'Offer deleted successfully');
    }
    
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/offers/{id}/security_deposit",
     *      summary="make security deposit on this offer",
     *      tags={"Offer"},
     *      description="Get Offer",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Offer",
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
     *                  ref="#/definitions/Offer"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function securityDeposit($id, OfferSecurityDepositAPIRequest $request)
    {
        $input = $request->all();
        
        /** @var Offer $offer */
        $offer = $this->offerRepository->findWithoutFail($id);
        if (empty($offer)) {
            return $this->sendError('Offer not found');
        }
        if (empty($offer->payout_id)) {
            return $this->sendError('No default payment method set');
        }
        if ($offer->security_holding_deposit_amount < config('business.payment.minimum_amount')) {
            return $this->sendError('amount is below what can be processed');
        }
        if ($offer->status == Offer::CANCEL) {
            return $this->sendError('offer is already cancelled');
        }
        if ($offer->status == Offer::INITIAL_DEPOSIT_MADE) {
            return $this->sendError('holding deposit already paid');
        }
        if ($offer->status != Offer::ACCEPT) {
            return $this->sendError('offer needs to be accepted first');
        }
        if (!isset($offer->currency)) {
            return $this->sendError("You have'nt set currency in your offer");
        }
        $now = Carbon::now();
        if ($offer->holding_deposit_expiry <= $now) {
          $offer->status = Offer::CANCEL;
          $offer->save();
          return $this->sendError('Holding deposit time expired');
        }
        $transactionRepository = \App::make(TransactionRepository::class);
        
        $transaction = $transactionRepository->getOrCreatePayoutTransaction($offer->payout, 
            $offer, $offer->currency, 
            $offer->security_holding_deposit_amount, Transaction::TITLE_INITIAL_DEPOSIT, [
            'description' => sprintf(Transaction::DESC_INITIAL_DEPOSIT, $offer->id),
          ], $offer->payout->token, $offer->holding_deposit_expiry);
        $updatedTransaction = $transactionRepository->payoutTransaction($offer->payout, $transaction);
        if ($updatedTransaction->status == \App\Models\Transaction::STATUS_FAILED) {
          return $this->sendError(sprintf('Payment failed because (%s)', $transaction->payment_error_message));
        }
        $input['status'] = Offer::INITIAL_DEPOSIT_MADE;
        $offer->{'transition'.Offer::INITIAL_DEPOSIT_MADE}();
        
        $this->offerRepository->setPresenter(OfferPresenter::class);
        $offer = $this->offerRepository->update($input, $id);

        return $this->sendResponse($offer['data'], 'Offer updated successfully');
    }
    
}
