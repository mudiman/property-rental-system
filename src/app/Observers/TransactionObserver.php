<?php 

namespace App\Observers;
use App\Models\Transaction;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use App\Support\Helper;

class TransactionObserver extends ParentObserver
{
    
    use DispatchesJobs;
    
    protected $transactionRepository;
    
    public function __construct() 
    {
      parent::__construct();
      $this->transactionRepository = \App::make(TransactionRepository::class);
    }
    
    public function deleted(Transaction $model)
    {
        
        $model->messages()->delete();
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->delete();
        }
        foreach(isset($model->childTransactions) ? $model->childTransactions: $model->childTransactions() as $tran) {
          $tran->delete();
        }
    }
        
    public function restored(Transaction $model)
    {
        $model->messages()->restore();
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->restore();
        }
        foreach(isset($model->childTransactions) ? $model->childTransactions: $model->childTransactions() as $tran) {
          $tran->restore();
        }
    }
    
    public function updated(Transaction $model)
    {
      $original = $model->getOriginal();
      $this->recordHistory($model, $original);
      
      if ($original['status'] != $model->status
          && $model->status == Transaction::STATUS_START
          && isset($model->payout_id)) {
        $this->dueTransactionNotification($model);
      }
      if ($original['status'] != $model->status
          && $model->status == Transaction::STATUS_FAILED
          && isset($model->payin_id)) {
        $this->failedPayinNotification($model);
      }
      if ($original['status'] != $model->status
          && $model->status == Transaction::STATUS_FAILED
          && isset($model->payout_id)) {
        $this->failedPayoutNotification($model);
      }
      if ($original['status'] != $model->status
          && $model->status == Transaction::STATUS_DONE
          && isset($model->payin_id)) {
        $this->successPayinNotification($model);
      }
      if ($original['status'] != $model->status
          && $model->status == Transaction::STATUS_DONE
          && isset($model->payout_id)) {
        $this->successPayoutNotification($model);
      }
    }
    
    private function dueTransactionNotification($model)
    {
      $this->transactionRepository->dispatchNotification('transaction.due_transaction', $model, config('business.admin.id'), $model->payout->user_id);
    }
    
    private function successPayinNotification($model)
    {
      $this->transactionRepository->dispatchNotification('transaction.payin_success', $model, config('business.admin.id'), $model->payin->user_id);
    }
    
    private function failedPayinNotification($model)
    {
      $this->transactionRepository->dispatchNotification('transaction.payin_failed', $model, config('business.admin.id'), $model->payin->user_id);
    }
    
    private function successPayoutNotification($model)
    { 
      $this->transactionRepository->dispatchNotification('transaction.payout_success', $model, config('business.admin.id'), $model->payout->user_id);
      if ($model->transactionable_type == \App\Models\Tenancy::morphClass) {
        $this->transactionRepository->dispatchNotification('transaction.payout_success_other', $model, config('business.admin.id'), $model->payout->user_id);
      }
      list($type, $factor) = Helper::calculateFactorDays($model->due_date, Carbon::now());
      $this->recordScore($model->transactionable, $model->payout->user_id, 
          config('business.scoring.default_type.transaction'),
          $type, $factor);
    }
    
    private function failedPayoutNotification($model)
    {
      $to = [$model->payout->user_id];
      if ($model->transactionable_type == \App\Models\Tenancy::morphClass) {
        $to[] = $model->transactionable->landlord_id;
      }
      
      $this->transactionRepository->dispatchNotification('transaction.payout_failed', $model, config('business.admin.id'), $to);
      
      list($type, $factor) = Helper::calculateFactorDays($model->due_date, Carbon::now());
      $this->recordScore($model->transactionable, $model->payout->user_id, 
          config('business.scoring.default_type.transaction'),
          0, 1 - $factor);
    }
    
}