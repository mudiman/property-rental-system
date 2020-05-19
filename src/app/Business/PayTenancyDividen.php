<?php

namespace App\Business;

use App\Repositories\TransactionRepository;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Support\Helper;
use App\Models\Tenancy;

/**
 * Description of PayTenancyDividen
 *
 * @author muda
 */
class PayTenancyDividen {
  //put your code here
  
  protected $sourceTransaction;
  protected $amount;
  protected $title;
  protected $description;
  protected $totalPayinDone = 0;
  protected $payinEntities = [];
  protected $transactionRepository;
  protected $tenancy;
  
  public function __construct($tenancy, $amount, $sourceTransaction, $title, $description) {
    Log::info(sprintf("PayTenancyDividen tenancy id %s amount to divide %s transaction source id %s title '%s'", 
        $tenancy->id, $amount, $sourceTransaction->id, $title));
    $this->sourceTransaction = $sourceTransaction;
    $this->tenancy = $tenancy;
    $this->amount = $amount;
    $this->title = $title;
    $this->description = $description;
    $this->transactionRepository = \App::make(TransactionRepository::class);
  }
  
  public function payDividen()
  {
    Log::info(sprintf("payDividen Start"));
    $this->calculateDivision();
    $this->createAllPayinTransactions();
    if ($this->sourceTransaction->transactionable->mode != Tenancy::MODE_MANUAL
        || ($this->sourceTransaction->transactionable->mode == Tenancy::MODE_MANUAL && $this->sourceTransaction->transactionable->property->letting_type == 'property')) {
      $this->payingAllTransactions();
    }
    if ($this->totalPayinDone == count($this->payinEntities)) {
      $this->sourceTransaction->dividen_done = true;
    }
    $this->sourceTransaction->save();
  }
  
  public function calculateDivision()
  {
    Log::info(sprintf("calculateDivision"));
    $this->calculateSmoorCommissionDivision();
    $this->calculatePropertyProDivision();
    $this->calculateAgencyDivision();
    $this->calculatePaymentGatewayCommissionDivision(); // just for tracking
    $this->calculateLandlordDivision();
    
    $this->sourceTransaction->transaction_data = json_encode([
      'total_payins' => count($this->payinEntities), 
      'total_payins_done' => 0,
    ]);
    $this->sourceTransaction->save();
  }
  
  private function calculatePropertyProDivision()
  {
    Log::info(sprintf("calculateLandlordDivision"));
    $totalPropertyProCommission = 0;
    $smoorCommission = 0;
    foreach ($this->tenancy->property->propertyProAcceptedRequests as $propertyProAcceptedRequest) {
      $originalCommission = $propertyProAcceptedRequest->getCommission($this->amount);
      list($smoor, $amount) = Helper::smoorCommissionAndRemaining($originalCommission, $this->sourceTransaction->currency);
      $smoorCommission += $smoor;
      
      $this->payinEntities[] = [
        'payin' => $propertyProAcceptedRequest->payinPropertyPro,
        'due_date' => $this->sourceTransaction->due_date,
        'amount' => $amount,
      ];
      $totalPropertyProCommission += $amount;
      Log::info(sprintf("calculatePropertyProDivision property pro id %s commission %s smoor share %s total so far  %s", 
          $propertyProAcceptedRequest->property_pro_id, $amount, $smoorCommission, $totalPropertyProCommission));
    }
    $this->sourceTransaction->property_pro_commission = $totalPropertyProCommission;
    $this->sourceTransaction->smoor_commission += $smoorCommission;
    return $totalPropertyProCommission;
  }
  
  private function calculateAgencyDivision()
  {
    Log::info(sprintf("calculateAgencyDivision"));
    $totalAgencyCommission = 0;
    $smoorCommission = 0;
    $uniqueAgencyList = [];
    foreach ($this->tenancy->property->propertyProAcceptedRequests as $propertyProAcceptedRequest) {
      $propertyPro = $propertyProAcceptedRequest->propertyPro;
      if (isset($propertyPro->myAgency) && !isset($uniqueAgencyList[$propertyPro->myAgency->id])) {
        $uniqueAgencyList[$propertyPro->myAgency->id] = true;
        $agencyOwnerPaying = $propertyPro->myAgency->ownerPayin();
        $agencyCommission = $propertyPro->myAgency->getCommission($this->amount);
        
        list($smoor, $amount) = Helper::smoorCommissionAndRemaining($agencyCommission, $this->sourceTransaction->currency);
        $smoorCommission += $smoor;
      
        $this->payinEntities[] = [
          'payin' => $agencyOwnerPaying,
          'amount' => $amount,
        ];
        Log::info(sprintf("calculateAgencyDivision agency id %s commission %s smoor share %s total so far  %s", 
          $agencyOwnerPaying->user_id, $amount, $smoorCommission, $totalAgencyCommission));
        $totalAgencyCommission += $agencyCommission;
      }
    }
    $this->sourceTransaction->agency_commission = $totalAgencyCommission;
    $this->sourceTransaction->smoor_commission += $smoorCommission;
    return $totalAgencyCommission;
  }
  
  private function calculateLandlordDivision()
  {
    Log::info(sprintf("calculateLandlordDivision"));
    $remainingAmount = $this->amount - $this->sourceTransaction->property_pro_commission 
        - $this->sourceTransaction->agency_commission 
        - $this->sourceTransaction->smoor_commission;
    $this->payinEntities[] = [
      'payin' => $this->tenancy->payinLandlord,
      'amount' => $remainingAmount,
      'transaction' => null
    ];
    Log::info(sprintf("calculateLandlordDivision payinEntities landlord %s payin id %s amount %s", 
        $this->tenancy->payinLandlord->user_id, $this->tenancy->payinLandlord->id, $remainingAmount));
    $this->sourceTransaction->landlord_commission = $remainingAmount;
    return $remainingAmount;
  }
  
  private function calculatePaymentGatewayCommissionDivision()
  {
    Log::info(sprintf("calculatePaymentGatewayCommissionDivision"));
    $this->sourceTransaction->payment_gateway_commission = Helper::paymentGatewayCommission($this->amount, $this->sourceTransaction->currency);
    Log::info(sprintf("calculatePaymentGatewayCommissionDivision %s", $this->sourceTransaction->payment_gateway_commission));
    return $this->sourceTransaction->payment_gateway_commission;
  }
  
  private function calculateSmoorCommissionDivision()
  {
    Log::info(sprintf("calculateSmoorCommissionDivision"));
    $this->sourceTransaction->smoor_commission = Helper::smoorCommission($this->amount, $this->sourceTransaction->currency);
    Log::info(sprintf("calculateSmoorCommissionDivision %s", $this->sourceTransaction->smoor_commission));
    return $this->sourceTransaction->smoor_commission;
  }
  
  public function createAllPayinTransactions()
  {
    Log::info(sprintf("createAllPayinTransactions"));
    foreach ($this->payinEntities as &$payinEntity) {
      $transaction = $this->prepPayInTransaction($payinEntity['payin'], $payinEntity['amount']);
      $payinEntity['transaction'] = $transaction;
    }
  }
  
  public function payingAllTransactions()
  {
    Log::info(sprintf("payingAllTransactions"));
    foreach ($this->payinEntities as $payinEntity) {
      $this->payEntity($payinEntity['payin'], $payinEntity['transaction']);
    }
  }
  
  public function prepPayInTransaction($payin, $amount)
  {
    Log::info(sprintf("prepPayInTransaction id %s amount %s", 
        $payin->id, $amount));
    $transaction = $this->transactionRepository->createPayinTransaction($payin, $this->tenancy, 
      $this->tenancy->offer->currency, $amount, $this->title, [
      'parent_transaction_id' => $this->sourceTransaction->id,
      'description' => $this->description. sprintf(' for user %s payin %s amount %s', $payin->user_id, $payin->id, $amount),
      'due_date' => $this->sourceTransaction->due_date
    ]);
    return $transaction;
  }
  
  public function payEntity($payin, $transaction)
  {
    Log::info(sprintf("payEntity"));
    $completeTransaction = $this->transactionRepository->payinTransaction($payin, $transaction);
    Log::info(sprintf("payEntity id %s status %s", 
        $payin->id, $completeTransaction->status));
    
    if ($completeTransaction->status == Transaction::STATUS_DONE) {
      $this->totalPayinDone++;
      $this->sourceTransaction->transaction_data = json_encode([
        'total_payins' => count($this->payinEntities),
        'total_payins_done' => $this->totalPayinDone,
      ]);
    }
    return $completeTransaction;
  }
}
