<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use Carbon\Carbon;

class TenancyTenantPayoutUpdateReminder extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tenancy:tenantPayoutUpdateReminder';
  protected $tenancyRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Tenant reminder to update payout card details';

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
    $remindDateTime = Carbon::now();

    $this->logInfo(sprintf("TenancyTenantPayoutUpdateReminder %s", $remindDateTime->toDateTimeString()));
    
    $this->updateReminderForFirstRentAndSecurty();
    $this->updateReminderFortRent();
    
  }
  
  private function updateReminderForFirstRentAndSecurty()
  {
    $remindDateTime = Carbon::now()->addDays(config('business.tenancy.payout.update_reminder'));
    
    $this->logInfo(sprintf("TenancyTenantPayoutUpdateReminder remind date %s", $remindDateTime->toDateTimeString()));
    
    $tenancies = Tenancy::where('status', Tenancy::SIGNING_COMPLETE)
        ->whereDay('checkin', '>=', $remindDateTime->day)
        ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
        ->with('payout')
        ->get();
    
    $this->logInfo(sprintf("TenancyTenantPayoutUpdateReminder found tenancies %s", count($tenancies)));
    
    foreach ($tenancies as $tenancy) {
      try {
        if ($tenancy->payout->used == true) {
          $this->tenancyRepository->dispatchNotification('tenancy.tenant_payout_update', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
        }
      } catch (Exception $e) {
        $this->error('Error TenancyTenantPayoutUpdateReminder updateReminderForFirstRentAndSecurty' . $e->getMessage());
      }
    }
  }
  
  private function updateReminderFortRent()
  {
    $remindDateTime = Carbon::now()->addDays(config('business.tenancy.payout.update_reminder'));
    
    $this->logInfo(sprintf("TenancyTenantPayoutUpdateReminder updateReminderFortRent remind date %s", $remindDateTime->toDateTimeString()));
    
    $tenancies = Tenancy::where('status', Tenancy::START)
        ->whereDay('checkin', '>=', $remindDateTime->day)
        ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
        ->with('payout')
        ->get();
    
    $this->logInfo(sprintf("TenancyTenantPayoutUpdateReminder updateReminderFortRent found tenancies %s", count($tenancies)));
    
    foreach ($tenancies as $tenancy) {
      try {
        if ($tenancy->payout->used == true) {
          $this->tenancyRepository->dispatchNotification('tenancy.tenant_payout_update', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
        }
      } catch (Exception $e) {
        $this->error('Error TenancyTenantPayoutUpdateReminder updateReminderFortRent' . $e->getMessage());
      }
    }
  }

}
