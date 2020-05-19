<?php

namespace App\Console\Commands\Test;

use App\Console\Commands\BaseCommand;
use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\Artisan;

class TimeShiftTenancy extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'test:timeShiftTenancy {id}';
  protected $tenancyRepository;
  protected $transactionRepository;
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'TimeShiftTenancy tenancy';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->currentDateTime = Carbon::now();
    $this->tenancyRepository = \App::make(TenancyRepository::class);
    $this->transactionRepository = \App::make(TransactionRepository::class);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {

    $this->logInfo(sprintf("TimeShiftTenancy tenany id %s between %s", $this->argument('id'), $this->currentDateTime->toDateTimeString()));
    
    $tenancy = $this->getTenancyDetail($this->argument('id'));
    $this->moveTenancy($tenancy);
    
    Artisan::call('tenancy:noticesReminder', []);
    Artisan::call('payment:create-upcoming-transaction', []);
    Artisan::call('payment:recurring', []);
    Artisan::call('tenancy:landlordPropertyProTDSReminder', []);
    Artisan::call('tenancy:timeElapsed', []);
    
  }
  
  protected function getTenancyDetail($id)
  {
    $tenancy = Tenancy::where('id', $id)
        ->with('offer')
        ->with('payout')
        ->with('payinLandlord')
        ->with('tenant')
        ->with('parentTenancy')
        ->with('landlord')
        ->with('property')->with('property.propertyProAcceptedRequests')
        ->with('property.propertyProAcceptedRequests.propetyPro')
        ->with('property.propertyProAcceptedRequests.propetyPro.agent')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency.agency')
        ->with('property.propertyProAcceptedRequests.propetyPro.payinPropertyPro')
        ->with('transactions')
        ->with('transactions.childTransactions')
        ->with('transactionLandlordSecurityDeposit')
        ->first();
    return $tenancy;
  }
  
  protected function moveTenancy($tenancy)
  {
    $now = $this->currentDateTime;
    
    $tenancy->checkin = $tenancy->checkin->subMonth(1);
    $tenancy->checkout = $tenancy->checkout->subMonth(1);
    if ($now->diffInDays($tenancy->checkin) >= 60) {
      $tenancy->actual_checkin = $tenancy->checkin->subMonth(1);
    }
    $tenancy->actual_checkout = $tenancy->actual_checkout->subMonth(1);
    $tenancy->due_date = $tenancy->due_date->subMonth(1);
    $tenancy->landlord_sign_datetime = $tenancy->landlord_sign_datetime->subMonth(1);
    $tenancy->tenant_sign_datetime = $tenancy->tenant_sign_datetime->subMonth(1);
    
    $tenancy->offer->checkin = $tenancy->offer->checkin->subMonth(1);
    $tenancy->offer->checkout = $tenancy->offer->checkout->subMonth(1);
    $tenancy->offer->save();
    
    foreach ($tenancy->transactions as &$transaction) {
      if (isset($transaction->due_date)) {
        $transaction->due_date = $transaction->due_date->subMonth(1);
      }
      $transaction->save();
    }
    
    $tenancy->save();
    
    // move parent tenancy
    if (!empty($tenancy->parentTenancy)) {
      $parentTenancy = $this->getTenancyDetail($tenancy->parentTenancy->id);
      $this->moveTenancy($parentTenancy);
    }
  }
}
