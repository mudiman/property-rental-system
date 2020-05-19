<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Models\Property;
use App\Models\Transaction;
use App\Repositories\TenancyRepository;
use \Carbon\Carbon;

class TenancyUpdate extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tenancy:timeElapsed';
  protected $tenancyRepository;
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Notify tenant to checkin tenancy after 7 days of checkin';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->currentDateTime = Carbon::now();
    $this->tenancyRepository = \App::make(TenancyRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {

    $this->logInfo(sprintf("TenancyUpdate between %s", $this->currentDateTime->toDateTimeString()));
    
    $this->startCheckInTenancy();
    $this->cancelUnpaidSecurityTenancy();
    
    $this->cancelExpiredTenancy();
    $this->noticeTenancyToComplete();
    $this->deleteCancelTenancy();
    
    $this->moveToRolling();
  }
  
  private function cancelExpiredTenancy() {
    try {
      $this->logInfo(sprintf("TenancyUpdate cancelExpiredTenancy between %s", $this->currentDateTime->toDateTimeString()));

      $tenancies = Tenancy::where('sign_expiry', '<', $this->currentDateTime->toDateTimeString())
          ->where('status', Tenancy::PRESIGN)
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $tenancy->status = Tenancy::CANCEL;
          $tenancy->save();
          
        } catch (Exception $e) {
          $this->error('Error TenancyUpdate cancelExpiredTenancy' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyUpdate cancelExpiredTenancy' . $e->getMessage());
    }
  }

  private function noticeTenancyToComplete() {
    try {
      $this->logInfo(sprintf("TenancyUpdate completedTenancyToNotice between %s", $this->currentDateTime->toDateTimeString()));

      $tenancies = Tenancy::whereDate('actual_checkout', '<=', $this->currentDateTime->toDateString())
          ->where('status', Tenancy::NOTICE)
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $tenancy->status = Tenancy::COMPLETE;
          $tenancy->save();
          
          $property = $tenancy->property;
          $property->status = Property::STATUS_DRAFT;
          $property->save();
        } catch (Exception $e) {
          $this->error('Error TenancyUpdate completedTenancyToNotice' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyUpdate completedTenancyToNotice' . $e->getMessage());
    }
  }
  
  private function moveToRolling() {
    try {
      $this->logInfo(sprintf("TenancyUpdate moveToRolling between %s", $this->currentDateTime->toDateTimeString()));

      $tenancies = Tenancy::whereDate('checkout', '<=', $this->currentDateTime->toDateString())
          ->whereIn('status', [Tenancy::START, Tenancy::PRE_NOTICE])
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $tenancy->status = Tenancy::ROLLING;
          $tenancy->save();
        } catch (Exception $e) {
          $this->error('Error TenancyUpdate moveToRolling' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyUpdate moveToRolling' . $e->getMessage());
    }
  }
  
  protected function deleteCancelTenancy() {
    try {
      $this->logInfo(sprintf("TenancyUpdate deleteCancelTenancy %s", $this->currentDateTime->toDateTimeString()));
      
      $tenancies = Tenancy::with('offer')->with('transactions')->with('parentTenancy')->with('reviews')
          ->where('updated_at', '<=', $this->currentDateTime->subHours(24)->toDateTimeString())
          ->where('status', Tenancy::CANCEL)
          ->get();
      
      foreach ($tenancies as $tenancy) {
        $tenancy->delete();
      }
    } catch (Exception $e) {
      $this->error('Error TenancyUpdate deleteCancelTenancy ' . $e->getMessage());
    }
  }
  
  private function startCheckinTenancy() {
    try {
      $this->logInfo(sprintf("TenancyUpdate startCheckInTenancy between %s", $this->currentDateTime->toDateTimeString()));

      $tenancies = Tenancy::whereDate('checkin', '<=', $this->currentDateTime->toDateString())
          ->whereIn('status', [Tenancy::SIGNING_COMPLETE])
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->with('transactionFirstRent')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          if (!empty($tenancy->transactionFirstRent) 
              && $tenancy->transactionFirstRent->status == Transaction::STATUS_DONE) {
            $tenancy->status = Tenancy::START;
            $tenancy->save();
          } else {
            $tenancy->status = Tenancy::CANCEL;
            $tenancy->save();
          }
        } catch (Exception $e) {
          $this->error('Error TenancyUpdate startCheckinTenancy' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyUpdate startCheckinTenancy' . $e->getMessage());
    }
  }
  
  private function cancelUnpaidSecurityTenancy() {
    try {
      $this->logInfo(sprintf("TenancyUpdate cancelUnpaidSecurityTenancy between %s", $this->currentDateTime->toDateTimeString()));

      $tenancies = Tenancy::where('status', Tenancy::SIGNING_COMPLETE)
          ->whereRaw('DATEDIFF(checkin, landlord_sign_datetime) > 3')
          ->whereRaw('DATEDIFF(checkin, current_date) < 28')
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $now = Carbon::now();
          $diffInDays = $tenancy->checkin->diffInDays($now);
          $plan_diffInDays = $tenancy->checkin->diffInDays($tenancy->landlord_sign_datetime);
          switch (true) {
            case $diffInDays  < 28 && $plan_diffInDays >= 30:
              $tenancy->status = Tenancy::CANCEL;
              $tenancy->transitioncancel();
              $tenancy->save();
              break;
            case ($plan_diffInDays - $diffInDays)  >= 3 && $plan_diffInDays < 30:
              $tenancy->status = Tenancy::CANCEL;
              $tenancy->transitioncancel();
              $tenancy->save();
              break;
          }
        } catch (Exception $e) {
          $this->error('Error TenancyUpdate cancelUnpaidSecurityTenancy' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyUpdate cancelUnpaidSecurityTenancy' . $e->getMessage());
    }
  }
}
