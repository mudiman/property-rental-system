<?php

namespace App\Repositories;

use App\Models\Tenancy;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Transaction;
use App\Business\PayTenancyDividen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelIntl\Facades\Currency;

class TenancyRepository extends ParentRepository
{

  use DispatchesJobs;

  protected $transactionRepository;

  public function __construct(\Illuminate\Container\Container $app) {
    parent::__construct($app);
    $this->transactionRepository = \App::make(TransactionRepository::class);
  }

  /**
   * @var array
   */
  protected $fieldSearchable = [
    'tenant_id',
    'property_id',
    'offer_id',
    'thread',
    'parent_tenancy_id',
    'landlord_id',
    'property_pro_id',
    'payout_id',
    'landlord_payin_id',
    'status',
    'mode',
    'previous_status',
    'type',
    'sign_expiry',
    'checkin',
    'checkout',
    'actual_checkin',
    'actual_checkout',
    'due_date',
    'due_amount',
    'tenant_sign_location',
    'tenant_sign_datetime',
    'landlord_sign_location',
    'landlord_sign_datetime',
    'special_condition',
    'landlord_notice_reminded',
    'tenant_notice_reminded',
    'updated_by',
    'created_by',
    'deleted_by'
  ];

  /**
   * Configure the Model
   * */
  public function model() {
    return Tenancy::class;
  }
  
  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
      'toUserId' => $to,
      'fromUserId' => $from,
      'tenant_first_name' => $model->tenant->first_name,
      'landlord_first_name' => $model->landlord->first_name,
      'propertypro_first_name' => isset($model->property_pro_id) ? $model->propertyPro->first_name : '',
      'arbitrator_first_name' => isset($model->property_pro_id) ? $model->propertyPro->first_name : $model->landlord->first_name,
      'property_title' => $model->property->title,
      'currency' => $model->offer->currency,
      'offer_amount' => $model->offer->rent,
      'due_date' => $model->due_date,
      'due_amount' => $model->due_amount,
      'format_due_amount' => Currency::format($model->due_amount, strtoupper($model->offer->currency)),
      'format_amount' => Currency::format($model->offer->rent, strtoupper($model->offer->currency)),
      'messageId' => $model->id,
      'messageType' => Tenancy::morphClass,
      'snapshot' => json_encode($model->toArray())
    ] ))->notify();
  }
  
  public function captureFirstRent($model) {
    $transaction = $this->capturePayment(
        $model, 
        Transaction::TITLE_FIRST_RENT, 
        sprintf(Transaction::DESC_FIRST_RENT, $model->id, $model->due_date->month),
        $model->offer->rent + $model->offer->security_deposit_amount - $model->offer->security_holding_deposit_amount
    );
    if ($transaction->status == Transaction::STATUS_DONE) {
      $model->status = Tenancy::START;
      $model->save();
      $this->payinSecurityDepositToLandlord($model, $transaction);
      $this->payDivided($model, $transaction, $model->offer->rent);
    }
    return $transaction;
  }
  
  public function payinSecurityDepositToLandlord($model, $sourceTransaction)
  {
    try {
    $transaction = $this->transactionRepository->createPayinTransaction(
        $model->payinLandlord, 
        $model, 
        $model->offer->currency, 
        $model->offer->security_deposit_amount, 
        Transaction::TITLE_LANDLORD_SECURITY_DEPOSIT, [
          'parent_transaction_id' => $sourceTransaction->id,
          'description' => sprintf(Transaction::DESC_LANDLORD_SECURITY_DEPOSIT, $model->offer->id)
      ]);
    $this->transactionRepository->payinTransaction($model->payinLandlord, $transaction);
    } catch (Exception $e) {
      Log::Error('payinSecurityDepositToLandlord', $e->getMessage());
    }
    return $transaction;
  }
  
  public function createPaymentTransaction(Tenancy $model, $title, $description, $captureAmount = null, $token = null, $due_date = null) {
    $amount = isset($captureAmount) ? $captureAmount: $model->offer->rent;
    $usingToken = isset($token) ? $token: $model->payout->token;
    $transaction_due_date = isset($due_date) ? $due_date: $model->due_date;
    $transaction = $this->transactionRepository->getOrCreatePayoutTransaction($model->payout, $model, $model->offer->currency, $amount, $title, [
      'description' => $description,
    ], $usingToken, $transaction_due_date);
    return $transaction;
  }
  
  public function capturePayment(Tenancy $model, $title, $description = null, $captureAmount = null, $token = null, $due_date = null) {
    $transaction = $this->createPaymentTransaction($model, $title, $description, $captureAmount, $token, $due_date);
    $updatedTransaction = $this->transactionRepository->payoutTransaction($model->payout, $transaction);
    return $updatedTransaction;
  }
  
  public function capturePaymentAndPayDividen(Tenancy $model, $title, $description, $captureAmount = null, $dividenAmount = null) {
    $amount = isset($captureAmount) ? $captureAmount: $model->offer->rent;
    $amountToDivide = isset($dividenAmount) ? $dividenAmount: $model->offer->rent;
    $transaction = $this->capturePayment($model, $title, $description, $amount);
    
    if ($transaction->status == Transaction::STATUS_DONE) {
      $model->due_amount -= $amount;
      $model->save();
      $this->payDivided($model, $transaction, $amountToDivide);
    } 
    return $transaction;
  }
  
  public function payDivided($model,$transaction, $amountToDivide = null)
  {
    $dividen = isset($amountToDivide) ? $amountToDivide: $model->offer->rent; 
    try {
      $payDividen = new PayTenancyDividen($model, 
          $dividen, 
          $transaction, 
          Transaction::TITLE_DEBIT_MONTHLY_RENT, 
          sprintf(Transaction::DESC_DEBIT_MONTHLY_RENT, $model->id, Carbon::now()->month)
      );
      $payDividen->payDividen();
    } catch (Exception $e) {
      Log::Error('payinSecurityDepositToLandlord', $e->getMessage());
    }
    return $payDividen;
  }
  
  public function getTenancyWithDetail($id) {
    $tenancy = Tenancy::where('id', $id)
        ->with('offer')
        ->with('payout')
        ->with('payinLandlord')
        ->with('tenant')
        ->with('landlord')
        ->with('transactionFirstRent')
        ->with('transactionsDue')
        ->with('property')->with('property.propertyProAcceptedRequests')
        ->with('property.propertyProAcceptedRequests.propetyPro')
        ->with('property.propertyProAcceptedRequests.propetyPro.agent')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency.agency')
        ->with('property.propertyProAcceptedRequests.propetyPro.payinPropertyPro')
        ->first();
    return $tenancy;
  }
  
  public function performDividenTransaction($transactonSource, $firstRent = false) {
    $tenancy = $this->getTenancyWithDetail($transactonSource->transactionable_id); 
    
    if ($firstRent) {
      $this->payinSecurityDepositToLandlord($tenancy, $transactonSource);
    }
    $this->payDivided($tenancy, $transactonSource);
  }
  
  public function makeAdvanceFirstRentTransaction($tenancy, $update = true) {
    $firstRent = $tenancy->transactionFirstRent()->first();
    if (!empty($firstRent)) return;

    $amount = $tenancy->offer->rent + $tenancy->offer->security_deposit_amount - $tenancy->offer->security_holding_deposit_amount;
    $transaction = $this->createPaymentTransaction($tenancy, Transaction::TITLE_FIRST_RENT, sprintf(Transaction::DESC_FIRST_RENT, $tenancy->id, Carbon::now()->month), $amount
    );
    if ($update) {
      $tenancy->due_date = $transaction->due_date->addMonths(1);
      $tenancy->due_amount = $amount;
      $tenancy->save();
    }
    return $transaction;
  }

}
