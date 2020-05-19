<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;

class TenancyLandlordPropertyProTDSReminder extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tenancy:landlordPropertyProTDSReminder';
  protected $tenancyRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Reminder';

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

    $remindDateTimeEnd = \Carbon\Carbon::now();
    $remindDateTimeStart = \Carbon\Carbon::now()->subDays(config('business.tenancy.tds_reminder'));


    $this->logInfo(sprintf("TenancyLandlordPropertyProTDSReminder between %s %s", $remindDateTimeStart->toDateTimeString(), $remindDateTimeEnd->toDateTimeString()));

    $tenancies = Tenancy::where('landlord_sign_datetime', '>', $remindDateTimeStart->startOfDay()->toDateTimeString())
        ->where('landlord_sign_datetime', '<', $remindDateTimeEnd->endOfDay()->toDateTimeString())
        ->where('status', Tenancy::SIGNING_COMPLETE)
        ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
        ->get();


    foreach ($tenancies as $tenancy) {
      try {
        $to = [$tenancy->landlord->id];
        if (isset($tenancy->property_pro_id)) $to[] = $tenancy->property_pro_id;

        $this->tenancyRepository->dispatchNotification('tenancy.tds_reminder', $tenancy, config('business.admin.id'), $to);
      } catch (Exception $e) {
        $this->error('Error TenancyLandlordPropertyProTDSReminder ' . $e->getMessage());
      }
    }
  }

}
