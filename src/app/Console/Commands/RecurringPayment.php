<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use App\Repositories\TransactionRepository;
use App\Models\Offer;
use App\Models\Transaction;
use Carbon\Carbon;

class RecurringPayment extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'payment:recurring';
  protected $tenancyRepository;
  protected $transactionRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Make tenancy recurring payment';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->tenancyRepository = \App::make(TenancyRepository::class);
    $this->transactionRepository = \App::make(TransactionRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $remindDateTime = Carbon::now();
    $this->logInfo(sprintf("RecurringPayment between %s ", $remindDateTime->toDateTimeString()));
    // first rent and security capture
    $this->performFirstPayoutsMonthlyRentPayment();
    // monthly rent payout
    $this->performPayoutsMonthlyRentPayment($remindDateTime);
    // payin incase payout didnt created divided can happen on manual payment
    $this->monthlyPayin($remindDateTime);
  }
  
  protected function performFirstPayoutsMonthlyRentPayment() {
    try {
      $startDate = Carbon::now()->startOfDay();
      $endDate = Carbon::now()->addDays(2)->endOfDay();
      
      $this->logInfo(sprintf("RecurringPayment performFirstPayoutsMonthlyRentPayment between %s ", $startDate->toDateString(), $endDate->toDateString()));
      
      $transactions = Transaction::where('due_date', '=', $startDate->toDateString())
          ->where('title', Transaction::TITLE_FIRST_RENT)
          ->where('status', Transaction::STATUS_START)
          ->whereNotNull('payout_id')
          ->with('transactionable')->with('transactionable.property')->with('transactionable.property.propertyProAcceptedRequests')
          ->with('transactionable.payinLandlord')
          ->with('payout')
          ->with('childTransactions')
          ->with('childTransactions.payin')
          ->with('childTransactions.payin.user')
          ->get();
      
      $this->logInfo(sprintf("performFirstPayoutsMonthlyRentPayment transactions found %s", count($transactions)));
      foreach ($transactions as $transaction) {
        try {
          $updatedTransaction = $this->transactionRepository->payoutTransaction($transaction->payout, $transaction, [
            'retries' => ++$transaction->retries
          ]);
          if ($updatedTransaction->status == Transaction::STATUS_DONE
              && !empty($transaction->transactionable->payinLandlord)) {
            $this->tenancyRepository->payinSecurityDepositToLandlord($transaction->transactionable, $transaction);
            $this->tenancyRepository->performDividenTransaction($transaction);
          }
        } catch (Exception $e) {
          $this->error('Error RecurringPayment performFirstPayoutsMonthlyRentPayment ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RecurringPayment performFirstPayoutsMonthlyRentPayment ' . $e->getMessage());
    }
  }
  
  protected function performPayoutsMonthlyRentPayment() {
    try {
      $startDate = Carbon::now()->startOfDay();
      $endDate = Carbon::now()->addDays(2)->endOfDay();
      
      $this->logInfo(sprintf("RecurringPayment performPayoutsMonthlyRentPayment between %s ", $startDate->toDateString(), $endDate->toDateString()));
      
      $transactions = Transaction::where('due_date', '<=', $startDate->toDateString())
          ->where('title', Transaction::TITLE_MONTHLY_RENT)
          ->where('status', Transaction::STATUS_START)
          ->whereNotNull('payout_id')
          ->with('transactionable')->with('transactionable.property')->with('transactionable.property.propertyProAcceptedRequests')
          ->with('transactionable.payinLandlord')
          ->with('payout')
          ->with('childTransactions')
          ->with('childTransactions.payin')
          ->with('childTransactions.payin.user')
          ->get();
      
      $this->logInfo(sprintf("performPayoutsMonthlyRentPayment transactions found %s", count($transactions)));
      foreach ($transactions as $transaction) {
        try {
          if ($transaction->transactionable->mode == Tenancy::MODE_MANUAL) continue;
          $updatedTransaction = $this->transactionRepository->payoutTransaction($transaction->payout, $transaction, [
            'retries' => ++$transaction->retries
          ]);
          if ($updatedTransaction->status == Transaction::STATUS_DONE
              && !empty($transaction->transactionable->payinLandlord)) {
            $this->tenancyRepository->performDividenTransaction($transaction);
          }
        } catch (Exception $e) {
          $this->error('Error RecurringPayment performPayoutsMonthlyRentPayment ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RecurringPayment performPayoutsMonthlyRentPayment ' . $e->getMessage());
    }
  }
  
  
  
  protected function monthlyPayin(Carbon $remindDateTime)
  {
    try {
      // due_date here is when tenancy due_date when this transaction was created
      $transactions = Transaction::where('due_date', '<=', $remindDateTime->toDateString()) 
          ->where(function ($query) {
            $query->where('title', Transaction::TITLE_MONTHLY_RENT)
                ->orWhere('title', Transaction::TITLE_FIRST_RENT);
          })
          ->where('status', Transaction::STATUS_DONE)
          ->where('dividen_done', false)
          ->whereNotNull('payout_id')
          ->with('transactionable')->with('transactionable.property')->with('transactionable.property.propertyProAcceptedRequests')
          ->with('transactionable.payinLandlord')
          ->with('payin')->with('payout')
          ->with('childTransactions')
          ->with('childTransactions.payin')
          ->with('childTransactions.payin.user')
          ->get();
      
      $this->logInfo(sprintf("RecurringPayment monthlyPayin found transaction %s", count($transactions)));
      foreach ($transactions as $transaction) {
        try {
          if ($transaction->childTransactions->isEmpty()
              && !empty($transaction->transactionable)) {
            $this->logInfo(sprintf("RecurringPayment monthlyPayin processing dividen tenancy id %s", $transaction->transactionable->id ));
            $this->tenancyRepository->performDividenTransaction($transaction);
          }
        } catch (Exception $e) {
          $this->error('Error RecurringPayment monthlyPayin ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RecurringPayment monthlyPayin ' . $e->getMessage());
    }
  }

}
