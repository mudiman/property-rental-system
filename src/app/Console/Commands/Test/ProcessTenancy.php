<?php

namespace App\Console\Commands\Test;

use Illuminate\Console\Command;
use App\Models\Tenancy;
use App\Repositories\TenancyRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use App\Models\Transaction;

class ProcessTenancy extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'test:tenancy {id} {action}';
  protected $tenancyRepository;
  protected $transactionRepository;
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Process tenancy';

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

    $this->logInfo(sprintf("ProcessTenancy between %s", $this->currentDateTime->toDateTimeString()));
    
    $tenancy = $this->getTenancyDetail();
    switch ($this->argument('action')) {
      case 'firstrenttransaction':
        $amount = $tenancy->offer->rent + $tenancy->offer->security_deposit_amount - $tenancy->offer->security_holding_deposit_amount;
        $transaction = $this->tenancyRepository->createPaymentTransaction($tenancy,
            Transaction::TITLE_FIRST_RENT, 
            sprintf(Transaction::DESC_FIRST_RENT, $tenancy->id, Carbon::now()->month),
            $amount
        );
        $tenancy->due_date = $transaction->due_date->addMonths(1);
        $tenancy->due_amount += $amount;
        $tenancy->save();
        break;
      case 'monthlytransaction':
        $amount = $tenancy->offer->rent;
        $transaction = $this->tenancyRepository->createPaymentTransaction($tenancy,
            Transaction::TITLE_MONTHLY_RENT, 
            sprintf(Transaction::DESC_MONTHLY_RENT, $tenancy->id, Carbon::now()->month),
            $tenancy->offer->rent
        );
        $tenancy->due_date = $transaction->due_date->addMonths(1);
        $tenancy->due_amount += $amount;
        $tenancy->save();
        break;
      case 'firstrent':
        $updatedTransaction = $this->transactionRepository->payoutTransaction($tenancy->payout, $tenancy->transactionFirstRent->first(), [
            'retries' => ++$tenancy->transactionFirstRent->first()->retries
        ]);
        if ($updatedTransaction->status == Transaction::STATUS_DONE) {
          $this->tenancyRepository->payinSecurityDepositToLandlord($tenancy, $updatedTransaction);
          $this->tenancyRepository->performDividenTransaction($updatedTransaction);
        }
        break;
      case 'rent':
        $updatedTransaction = $this->transactionRepository->payoutTransaction($tenancy->payout, $tenancy->transactionsDue->first(), [
            'retries' => ++$tenancy->transactionsDue->first()->retries
        ]);
        if ($updatedTransaction->status == Transaction::STATUS_DONE) {
          $this->tenancyRepository->performDividenTransaction($updatedTransaction);
        }
        break;
      case 'checkin':
        $this->tenancyRepository->dispatchNotification('tenancy.checkin_reminder', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
        break;
      case 'prenotice_landlord':
        $this->tenancyRepository->dispatchNotification('tenancy.prenotice_landlord', $tenancy, config('business.admin.id'), $tenancy->landlord->id);
        break;
      case 'landlord_2month_notice_reminder':
        $this->tenancyRepository->dispatchNotification('tenancy.landlord_2month_notice_reminder', $tenancy, config('business.admin.id'), $tenancy->landlord->id);
        break;
      case 'tds_reminder':
        $this->tenancyRepository->dispatchNotification('tenancy.tds_reminder', $tenancy, config('business.admin.id'), $tenancy->landlord->id);
        break;
      case 'prenotice_tenant':
        $this->tenancyRepository->dispatchNotification('tenancy.prenotice_tenant', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
        break;
      case 'tenant_payout_update':
        $this->tenancyRepository->dispatchNotification('tenancy.tenant_payout_update', $tenancy, config('business.admin.id'), $tenancy->tenant->id);
        break;
    }
  }
  
  protected function getTenancyDetail()
  {
    $tenancy = Tenancy::where('id', $this->argument('id'))
        ->with('offer')
        ->with('payout')
        ->with('payinLandlord')
        ->with('tenant')
        ->with('landlord')
        ->with('property')->with('property.propertyProAcceptedRequests')
        ->with('property.propertyProAcceptedRequests.propetyPro')
        ->with('property.propertyProAcceptedRequests.propetyPro.agent')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency')
        ->with('property.propertyProAcceptedRequests.propetyPro.myAgency.agency')
        ->with('property.propertyProAcceptedRequests.propetyPro.payinPropertyPro')
        ->with('transactionFirstRent')
        ->with('transactionFirstRent.childTransactions')
        ->with('transactionCurrentMonthRent')
        ->with('transactionCurrentMonthRent.childTransactions')
        ->with('transactionLandlordSecurityDeposit')
        ->first();
    return $tenancy;
  }
}
