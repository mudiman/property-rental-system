<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;

class TenancyNoticesReminder extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tenancy:noticesReminder';
  protected $tenancyRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Notice reminder to landlord and tenant before 2 month and 1 week for requesting renewal or canceling tenancy';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();

    $this->tenancyRepository = \App::make(TenancyRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $remindDateTime = \Carbon\Carbon::now();
    $this->logInfo(sprintf("TenancyNoticesReminder %s ", $remindDateTime->toDateTimeString()));
    
    $this->prenoticeLandlordReminder();
    $this->prenoticeTenantReminder();
    $this->noticeReminder();
  }

  private function prenoticeLandlordReminder()
  {
    try {
      $remindDateTimeEnd = \Carbon\Carbon::now()->addDays(config('business.tenancy.prenotice.end'))->endOfDay();
      $remindDateTimeStart = \Carbon\Carbon::now()->addDays(config('business.tenancy.prenotice.start'))->startOfDay();

      $this->logInfo(sprintf("TenancyNoticesReminder prenoticeLandlordReminder between %s %s", $remindDateTimeStart->toDateTimeString(), $remindDateTimeEnd->toDateTimeString()));

      $tenancies = Tenancy::where('checkout', '>', $remindDateTimeStart->toDateString())
          ->where('checkout', '<', $remindDateTimeEnd->toDateString())
          ->where('status', Tenancy::START)
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $this->tenancyRepository->dispatchNotification('tenancy.prenotice_landlord', $tenancy, config('business.admin.id'), $tenancy->landlord->id);
        } catch (Exception $e) {
          $this->error('Error TenancyNoticesReminder prenoticeLandlordReminder ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyNoticesReminder prenoticeReminder ' . $e->getMessage());
    }
  }
  
  private function prenoticeTenantReminder()
  {
    try {
      $remindDateTime = \Carbon\Carbon::now();

      $this->logInfo(sprintf("TenancyNoticesReminder prenoticeTenantReminder %s", $remindDateTime->toDateTimeString()));

      $tenancies = Tenancy::where('status', Tenancy::PRE_NOTICE)
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $this->tenancyRepository->dispatchNotification('tenancy.prenotice_tenant', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
        } catch (Exception $e) {
          $this->error('Error TenancyNoticesReminder prenoticeTenantReminder ' . $e->getMessage());
        }
      }
    
    } catch (Exception $e) {
      $this->error('Error TenancyNoticesReminder prenoticeTenantReminder ' . $e->getMessage());
    }
  }

  private function noticeReminder() {
    try {
      $remindDateTimeStart = \Carbon\Carbon::now()->addDays(config('business.tenancy.notice.start'));

      $this->logInfo(sprintf("TenancyNoticesReminder noticeReminder fron %s ", $remindDateTimeStart->toDateTimeString()));

      $tenancies = Tenancy::where('checkout', '>', $remindDateTimeStart->toDateString()) //note keep notifying thats why open comparison
          ->where('status', Tenancy::PRE_NOTICE)
          ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
          ->get();

      foreach ($tenancies as $tenancy) {
        try {
          $this->tenancyRepository->dispatchNotification('tenancy.landlord_2month_notice_reminder', $tenancy, config('business.admin.id'), $tenancy->landlord->id);
        } catch (Exception $e) {
          $this->error('Error TenancyNoticesReminder noticeReminder ' . $e->getMessage());
        }
      }
    } catch (Exception $e) {
      $this->error('Error TenancyNoticesReminder noticeReminder ' . $e->getMessage());
    }
  }

}
