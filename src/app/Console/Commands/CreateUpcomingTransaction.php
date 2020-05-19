<?php

namespace App\Console\Commands;

use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use App\Repositories\TransactionRepository;
use App\Models\Offer;
use App\Models\Transaction;
use Carbon\Carbon;

class CreateUpcomingTransaction extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'payment:create-upcoming-transaction';
  protected $tenancyRepository;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Make without processing 10 days in advance transaction for upcomming payment';

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
    $this->logInfo(sprintf("CreateUpcomingTransaction between %s ", $remindDateTime->toDateTimeString()));
    
    $this->makeAdvanceFirstRentTransaction();
    // monthly rent
    $this->makeAdvanceTransaction();
  }
  
  protected function makeAdvanceTransaction()
  {
    /**
     * Avoid doing for paid current month
     */
    $startDateAdv = Carbon::now()->addDays(9);
    $endDateAdv = Carbon::now()->addDays(11);
    
    $this->logInfo(sprintf("CreateUpcomingTransaction makeAdvanceTransaction advance date between %s - %s", $startDateAdv->toDateTimeString(), $endDateAdv->toDateTimeString()));
    
    $tenancies = Tenancy::whereRaw('abs(datediff(current_date, due_date)) < 10')
        ->where('status', Tenancy::START)
        ->where('type', Offer::TYPE_LONG)
        ->with('tenant')->with('payout')->with('offer')
        ->with('landlord')->with('payinLandlord')
        ->with('property')->with('property.propertyProAcceptedRequests')
        ->with('property.propertyProAcceptedRequests.propetyPro')
        ->with('property.propertyProAcceptedRequests.propetyPro.agent')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency.agency')
        ->with('property.propertyProAcceptedRequests.propetyPro.payinPropertyPro')
        ->with('transactionCurrentMonthRent')
        ->with('transactionCurrentMonthRent.childTransactions')
        ->get();
    
    $this->logInfo(sprintf("CreateUpcomingTransaction monthlyRent tenancies found %s", count($tenancies)));
    
    foreach ($tenancies as $tenancy) {
      if (!empty($tenancy->transactionCurrentMonthRent)) return;
      $amount = $tenancy->offer->rent;
      $transaction = $this->tenancyRepository->createPaymentTransaction($tenancy,
          Transaction::TITLE_MONTHLY_RENT, 
          sprintf(Transaction::DESC_MONTHLY_RENT, $tenancy->id, Carbon::now()->month),
          $amount
      );
      $tenancy->due_date = $transaction->due_date->addMonths(1);
      $tenancy->due_amount += $amount;
      $tenancy->save();
    }
  }
  
  protected function makeAdvanceFirstRentTransaction()
  {
    /**
     * Avoid doing for paid current month
     */
    $startDateAdv = Carbon::now()->addDays(9);
    $endDateAdv = Carbon::now()->addDays(11);
    
    $this->logInfo(sprintf("CreateUpcomingTransaction makeAdvanceFirstRentTransaction advance date between %s - %s", $startDateAdv->toDateTimeString(), $endDateAdv->toDateTimeString()));
    
    $tenancies = Tenancy::whereRaw('abs(datediff(current_date, due_date)) < 10')
        ->where('status', Tenancy::SIGNING_COMPLETE)
        ->where('type', Offer::TYPE_LONG)
        ->with('tenant')->with('payout')->with('offer')
        ->with('landlord')->with('payinLandlord')
        ->with('property')->with('property.propertyProAcceptedRequests')
        ->with('property.propertyProAcceptedRequests.propetyPro')
        ->with('property.propertyProAcceptedRequests.propetyPro.agent')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency.agency')
        ->with('property.propertyProAcceptedRequests.propetyPro.payinPropertyPro')
        ->with('transactionFirstRent')
        ->with('transactionCurrentMonthRent')
        ->with('transactionCurrentMonthRent.childTransactions')
        ->get();
    
    $this->logInfo(sprintf("CreateUpcomingTransaction monthlyRent tenancies found %s", count($tenancies)));
    
    foreach ($tenancies as $tenancy) {
      if (!empty($tenancy->transactionFirstRent)) return;
      $this->tenancyRepository->makeAdvanceFirstRentTransaction($tenancy);
    }
  }

}
