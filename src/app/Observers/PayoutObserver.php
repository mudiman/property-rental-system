<?php 

namespace App\Observers;

use App\Models\Payout;
use App\Repositories\PayoutRepository;

class PayoutObserver extends ParentObserver
{

    protected $payoutRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->payoutRepository = \App::make(PayoutRepository::class);
    }
    
    public function deleted(Payout $model)
    {
        $model->messages()->delete();
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->delete();
        }
        foreach(isset($model->transactions) ? $model->transactions: $model->transactions() as $tran) {
          $tran->delete();
        }
    }
        
    public function restored(Payout $model)
    {
        $model->messages()->restore();
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->restore();
        }
        foreach(isset($model->transactions) ? $model->transactions: $model->transactions() as $tran) {
          $tran->restore();
        }
    }
    
    public function saving(Payout $model)
    {
      if (!Payout::$FIRE_EVENTS) return;
      Payout::$FIRE_EVENTS = false;
      if (isset($model->user_reference)) {
        $this->payoutRepository->updatePaymentGatewayCustomer($model);
      } else {
        $this->payoutRepository->createPaymentGatewayMethod($model);
      }
    }
    
    public function updated(Payout $model)
    {
      //tracking history
      $original = $model->getOriginal();
      $this->recordHistory($model, $original);
    }
}