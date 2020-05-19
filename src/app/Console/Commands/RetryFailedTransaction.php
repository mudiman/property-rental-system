<?php

namespace App\Console\Commands;

use App\Repositories\TransactionRepository;
use App\Repositories\TenancyRepository;
use App\Models\Transaction;
use Carbon\Carbon;

class RetryFailedTransaction extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'payment:retryFailed';
  protected $startDate;
  protected $endDate;
  protected $transactionRepository;
  protected $tenancyRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Retry failed payment';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->startDate = Carbon::now()->subDays(30)->startOfDay();
    $this->endDate = Carbon::now()->endOfDay();
    $this->transactionRepository = \App::make(TransactionRepository::class);
    $this->tenancyRepository = \App::make(TenancyRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->logInfo(sprintf("RetryFailedTransaction from %s ", $this->startDate->toDateTimeString()));
//    $this->performFailedPayoutMonthlyRentPayment();
//    $this->performFailedPayoutFirstRentWithSecurityPayment();
    $this->performFailedPayoutRefundPayment();
    $this->performFailedPayinPayment();
  }


  protected function performFailedPayoutRefundPayment() {
    try {
      $transactions = Transaction::where('created_at', '>=', $this->startDate->toDateTimeString())
          ->where('created_at', '<=', $this->endDate->toDateTimeString())
          ->where('status', Transaction::STATUS_DONE)
          ->where('refund_status', Transaction::STATUS_FAILED)
          ->whereNotNull('payout_id')
          ->with('transactionable')->with('transactionable.property')->with('transactionable.property.propertyProAcceptedRequests')
          ->with('transactionable.payinLandlord')
          ->with('user')
          ->with('payin')->with('payout')
          ->with('childTransactions')
          ->get();

      foreach ($transactions as $transaction) {
        try {
          $updatedTransaction = $this->transactionRepository->refundTransaction($transaction->payout, $transaction, [
            'retries' => ++$transaction->retries
          ]);
        } catch (Exception $e) {
          $this->error('Error RetryFailedTransaction performFailedPayoutRefundPayment ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RetryFailedTransaction performFailedPayoutRefundPayment ' . $e->getMessage());
    }
  }

  protected function performFailedPayoutMonthlyRentPayment() {
    try {
      $transactions = Transaction::where('created_at', '>=', $this->startDate->toDateTimeString())
          ->where('due_date', '<=', $this->endDate->toDateString())
          ->where('due_date', '>=', $this->startDate->toDateString())
          ->where('title', Transaction::TITLE_MONTHLY_RENT)
          ->where('status', Transaction::STATUS_FAILED)
          ->whereNotNull('payout_id')
          ->with('transactionable')->with('transactionable.property')->with('transactionable.property.propertyProAcceptedRequests')
          ->with('transactionable.payinLandlord')
          ->with('payout')
          ->with('childTransactions')
          ->with('childTransactions.payin')
          ->with('childTransactions.payin.user')
          ->get();
      
      $this->logInfo(sprintf("performFailedPayoutMonthlyRentPayment transactions found %s", count($transactions)));
      foreach ($transactions as $transaction) {
        try {
          $updatedTransaction = $this->transactionRepository->payoutTransaction($transaction->payout, $transaction, [
            'retries' => ++$transaction->retries
          ]);
          if ($updatedTransaction->status == Transaction::STATUS_DONE) {
            $this->tenancyRepository->performDividenTransaction($transaction);
          }
        } catch (Exception $e) {
          $this->error('Error RetryFailedTransaction performFailedPayoutMonthlyRentPayment ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RetryFailedTransaction performFailedPayoutMonthlyRentPayment ' . $e->getMessage());
    }
  }
  
  protected function performFailedPayoutFirstRentWithSecurityPayment() {
    try {
      $transactions = Transaction::where('created_at', '>=', $this->startDate->toDateTimeString())
          ->where('due_date', '<=', $this->endDate->toDateString())
          ->where('due_date', '>=', $this->startDate->toDateString())
          ->where('title', Transaction::TITLE_FIRST_RENT)
          ->where('status', Transaction::STATUS_FAILED)
          ->where('transactionable_type', \App\Models\Tenancy::morphClass)
          ->whereNotNull('payout_id')
          ->with('transactionable')
          ->with('payout')
          ->with('childTransactions')
          ->with('childTransactions.payin')
          ->with('childTransactions.payin.user')
          ->get();
      
      $this->logInfo(sprintf("performFailedPayoutFirstRentWithSecurityPayment transactions found %s", count($transactions)));
      foreach ($transactions as $transaction) {
        try {
          $updatedTransaction = $this->transactionRepository->payoutTransaction($transaction->payout, $transaction, [
            'retries' => ++$transaction->retries
          ]);
          if ($updatedTransaction->status == Transaction::STATUS_DONE) {
            $this->tenancyRepository->performDividenTransaction($transaction, true);
          }
        } catch (Exception $e) {
          $this->error('Error RetryFailedTransaction performFailedPayoutFirstRentWithSecurityPayment ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RetryFailedTransaction performFailedPayoutFirstRentWithSecurityPayment ' . $e->getMessage());
    }
  }

  protected function performFailedPayinPayment() {
    try {
      $transactions = Transaction::where('created_at', '>', $this->startDate->toDateTimeString())
          ->where('created_at', '<', $this->endDate->toDateTimeString())
          ->where('status', Transaction::STATUS_FAILED)
          ->whereNotNull('payin_id')
          ->with('transactionable')->with('user')
          ->with('payin')
          ->with('sourceTransaction')
          ->with('sourceTransaction.childTransactions')
          ->get();
      
      $this->logInfo(sprintf("performFailedPayinPayment transactions found %s", count($transactions)));
      foreach ($transactions as $transaction) {
        try {
          $updatedTransaction = $this->transactionRepository->payinTransaction($transaction->payin, $transaction, [
            'retries' => ++$transaction->retries
          ]);
          
          if ($updatedTransaction->status == Transaction::STATUS_DONE) {
            // update source payout transaction if all dividen done
            $childDoneCount = 1; // because current one is done
            foreach ($transaction->sourceTransaction->childTransactions as $childTransaction) {
              if ($childTransaction->status == Transaction::STATUS_DONE
                  && $childTransaction->id != $updatedTransaction->id) { // avoid current done one
                $childDoneCount++;
              }
            }
            if ($childDoneCount == count($transaction->sourceTransaction->childTransactions)) {
              $transaction->sourceTransaction->dividen_done = true;
              $transaction->sourceTransaction->save();
            }
          }
        } catch (Exception $e) {
          $this->error('Error RetryFailedTransaction performFailedPayinPayment ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error RetryFailedTransaction performFailedPayinPayment' . $e->getMessage());
    }
  }

}
