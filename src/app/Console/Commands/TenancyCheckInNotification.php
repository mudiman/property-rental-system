<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use \Carbon\Carbon;

class TenancyCheckInNotification extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'tenancy:checkinNotification';
  protected $tenancyRepository;

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

    $this->tenancyRepository = \App::make(TenancyRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {

    $remindDateTimeStart = \Carbon\Carbon::now()->subDays(config('business.tenancy.checkin_reminder') + 1)->startOfDay();
    $remindDateTimeEnd = \Carbon\Carbon::now()->subDays(config('business.tenancy.checkin_reminder'))->endOfDay();

    $this->logInfo(sprintf("TenancyCheckInNotification between %s %s", $remindDateTimeStart->toDateTimeString(), $remindDateTimeEnd->toDateTimeString()));

    $tenancies = Tenancy::where('checkin', '>', $remindDateTimeStart->toDateString())
        ->where('checkin', '<', $remindDateTimeEnd->toDateString())
        ->where('status', Tenancy::START)
        ->with('tenant')->with('landlord')->with('property')->with('propertyPro')
        ->get();

    foreach ($tenancies as $tenancy) {
      try {
        $this->tenancyRepository->dispatchNotification('tenancy.checkin_reminder', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
      } catch (Exception $e) {
        $this->error('Error TenancyCheckInNotification ' . $e->getMessage());
      }
    }
  }
}
