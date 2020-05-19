<?php

namespace App\Console\Commands\Import;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Property;
use App\Models\Offer;
use App\Models\Tenancy;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use App\Models\Payin;

class ImportTenant extends ImportLandlord {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:tenant {file}';
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import tenant';

  protected $tenantMapping = [
    "property_ref" => null,
    "tenant" => ["first_name", "last_name"],
    "dob" => "date_of_birth",
    "gender" => "gender",
    "nationality" => "nationality",
    "landline" => "phone",
    "mobile" => "mobile",
    "email" => "email",
    "contract_start_date" => null,
    "contract_end_date" => null,
    "special_conditions" => "configuration",
    "rent_payment_date" => null,
    "payment_received" => null,
  ];
  
  protected $offerMapping = [
    "contract_start_date" => "checkin",
    "contract_end_date" => "checkout",
    "currently_rented_for" => "rent_per_month",
    "rent" => "rent_per_month",
  ];
  
   protected $dateTimeAttributes = [
    "checkin",
    "checkout",
    "date_of_birth"
  ];
  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    $this->currentDateTime = Carbon::now();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->info(sprintf("ImportTenant between %s", $this->currentDateTime->toDateTimeString()));

    $this->import();
  }

  protected function import() {
    $data = Excel::load(storage_path('csv') . '/'.$this->argument('file').'.csv', function($results) {})->get();

    if (!empty($data) && $data->count()) {
      foreach ($data as $row) {
        if (empty($row['email'])) continue;
        $record = $this->parseData($row, $this->tenantMapping);
        //offer
        $offer = $this->parseData($row, $this->offerMapping);
        if (!empty($record)) {
          $this->insertAndUpdate($record, [
            'property' => isset($row['property_ref']) ? $row['property_ref']: null,
            'offer' => [
              'checkin' => $offer['checkin'],
              'checkout' => $offer['checkout'],
              'rent_per_month' => isset($offer['rent_per_month']) ? $offer['rent_per_month']:null,
            ],
            'transactions' => [
              'history' => $row['rent_history'],
              'landlord_commission' => isset($row['rent_paid_to_landlord']) ? $row['rent_paid_to_landlord']: 0,
              'smoor_commission' => isset($row['rent_paid_to_smoor']) ? $row['rent_paid_to_smoor']: 0,
            ]
          ]);
        }
      }
    }
  }
  
  protected function insertAndUpdate($record, $params) {
    $record += [
      'push_notification_messages' => false,
      'push_notification_viewings' => false,
      'push_notification_offers' => false,
      'push_notification_other' => false,
      'email_notification_messages' => false,
      'email_notification_viewings' => false,
      'email_notification_offers' => false,
      'email_notification_other' => false,
      'text_notification_messages' => false,
      'text_notification_viewings' => false,
      'text_notification_offers' => false,
      'text_notification_other' => false,
      'admin_verified' => 1,
      'password' => Hash::make('Smoor_123'),
    ];
    
    $user = User::updateOrCreate(['email' => $record['email']], $record);
    if (!empty($params['property'])) {
      $property = Property::where('reference', $params['property'])->first();
    }
    
    if (isset($params['offer']) && !empty($params['property'])) {
      $offerData = $params['offer'];
      
      if (empty($offerData['rent_per_month'])) {
        $offerData['rent_per_month'] = $property->rent_per_month;
      }
      
      $offerData += [
        'tenant_id' => $user->id,
        'landlord_id' => $property->landlord_id,
        'property_id' => $property->id,
        'rent_per_month' => $offerData['rent_per_month'],
        'currency' => 'gbp',
        'thread' => uniqid(),
        'status' => Offer::INITIAL_DEPOSIT_MADE,
      ];
      $due_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, Carbon::parse($offerData['checkin'])->day);
      $offer = Offer::firstOrNew([
        'tenant_id' => $user->id,
        'property_id' => $property->id,
      ]);
      $offer->fill($offerData);
      $offer->save();
      $offer->status = Offer::INITIAL_DEPOSIT_MADE;
      $offer->save();
      
      $offerData += [
        'actual_checkin' => $offerData['checkin'],
        'actual_checkout' => $offerData['checkout'],
        'mode' => Tenancy::MODE_MANUAL,
      ];
      
      $tenancy = Tenancy::firstOrNew([
        'offer_id' => $offer->id
      ]);
      $tenancy->fill($offerData);
      
      $payin = Payin::where('user_id', $tenancy->landlord_id)->first();
      if (!empty($payin)) {
        $tenancy->landlord_payin_id = $payin->id;
      }
      
      $tenancy->offer_id = $offer->id;
      $tenancy->status = Tenancy::START;
      $tenancy->due_date = $due_date;
      $tenancy->due_amount = $offerData['rent_per_month'];
      $tenancy->save();
      $tenancy->status = Tenancy::START;
      $tenancy->save();
      
      if (isset($tenancy->checkout)
          && Carbon::now() < $tenancy->checkout) {
        $property->status = Property::STATUS_OCCUPIED;
        $property->save();
      }
      
      if (isset($params['transactions'])) {
        $transactionData = $params['transactions'];
        $transactionDueDates = explode(";", $transactionData['history']);
        foreach ($transactionDueDates as $due_date) {
          if (!empty(trim($due_date))) {
            $carbon_due_date = $this->parseDateTime($due_date, true);
            $this->makeRentTransaction($user->id, $tenancy, $carbon_due_date, 
                $offerData['rent_per_month'], $transactionData['landlord_commission'], $transactionData['smoor_commission'], $payin);
          }
        }
        if (!empty(trim($due_date))) {
          $tenancy->due_date = $due_date;
          $tenancy->save();
        }
      }
    }
  }
  
  private function makeRentTransaction($tenant_id, $tenancy, Carbon $due_date, $amount, $landlord_amount, $smoor_commission, $payin) {
    $transaction = Transaction::firstOrNew([
        'due_date' => $due_date->toDateString(),
        'user_id' => $tenant_id,
        'title' => Transaction::TITLE_MONTHLY_RENT,
    ]);
    $transaction->user_id = $tenant_id;
    $transaction->due_date = $due_date;
    $transaction->amount = $amount;
    $transaction->currency = 'gbp';
    $transaction->dividen_done = true;
    $transaction->landlord_commission = $landlord_amount;
    $transaction->smoor_commission = $smoor_commission;
    $transaction->status = Transaction::STATUS_DONE;
    $transaction->description = sprintf(Transaction::DESC_MONTHLY_RENT, $tenancy->id, $due_date->month);
    $transaction->transactionable_id = $tenancy->id;
    $transaction->transactionable_type = Tenancy::morphClass;
    $transaction->save();
    
    $transactionPayin = Transaction::firstOrNew([
        'due_date' => $due_date->toDateString(),
        'user_id' => $tenancy->landlord_id,
        'title' => Transaction::TITLE_MONTHLY_RENT,
    ]);
    
    if (!empty($payin)) {
      $transactionPayin->payin_id = $payin->id;
    }
    $transactionPayin->parent_transaction_id = $transaction->id;
    $transaction->amount = $landlord_amount;
    $transaction->currency = 'gbp';
    $transactionPayin->status = Transaction::STATUS_DONE;
    $transactionPayin->title = Transaction::TITLE_MONTHLY_RENT;
    $transactionPayin->description = sprintf(Transaction::DESC_MONTHLY_RENT, $tenancy->id, $due_date->month);
    $transactionPayin->transactionable_id = $tenancy->id;
    $transactionPayin->transactionable_type = Tenancy::morphClass;
    $transactionPayin->save();
    
    // update tenancy due date if greater
    if ($due_date > $tenancy->due_date) {
      $tenancy->due_date = $due_date;
      $tenancy->save();
    }
  }

}
