<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Payin;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;

class ImportLandlord extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:landlord {file}';
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import landlord';

  protected $landlordMapping = [
    "property_ref" => "reference",
    "owner" => ["first_name", "last_name"],
    "dob" => "date_of_birth",
    "landlord" => ["first_name", "last_name"],
    "address_line_1" => "address",
    "address_line_2" => "address",
    "address_line_3" => "address",
    "town" => "county",
    "postcode" => "postal_code",
    "landline" => "phone",
    "mobile" => "mobile",
    "email" => "email",
    "passport_verified" => null,
    "driving_licence_verified" => null,
    "location" => "Essex",
    "profession" => "profession",
  ];
  
  protected $payinMapping = [
    "sort_code" => "sort_code",
    "postcode" => "postal_code",
    "owner" => ["first_name", "last_name"],
    "country_code" => "countryCode",
    "dob" => "date_of_birth",
    "account_number" => "account_number",
  ];
  
  protected $dateTimeAttributes = [
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
    $this->info(sprintf("ImportLandlord between %s", $this->currentDateTime->toDateTimeString()));

    $this->import();
  }

  protected function import() {
    $data = Excel::load(storage_path('csv').'/'.$this->argument('file').'.csv', function($results) {})->get();

    if (!empty($data) && $data->count()) {
      foreach ($data as $row) {
        if (empty($row['property_ref']) || empty($row['email'])) continue;
        $record = $this->parseData($row, $this->landlordMapping);
        //payin
        $payins = $this->parseData($row, $this->payinMapping);
        if (!empty($record)) {
          $this->insertAndUpdate($record, [
            'payins' => $payins,
            'property' => $row['property_ref']
          ]);
        }
      }
    }
  }
  
  protected function parseData($row, $mapping) {
    $record = [];
    foreach ($row as $key => $value) {
      $newKey = $this->getFieldData($mapping, $key, $value);
      if (!$newKey) continue;
      if (is_array($newKey)) {
        $this->setMultipleFieldValue($record, $newKey);
      } else {
        $this->setFieldValue($record, $newKey, $value);
      }
    }
    return $record;
  }
  
  protected function insertAndUpdate($record, $params) {
    $record['role'] = 'tenant,landlord';
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
    Payin::$FIRE_EVENTS = false;
    $host= gethostname();
    $ip = gethostbyname($host);
    $payin = Payin::updateOrCreate([
      'user_id' => $user->id, 
      'account_number' => $params['payins']['account_number']], 
        $params['payins'] + [
          'ip' => $ip,
          'currency' => 'GBP',
          "entity_type" => "individual",
        ]);
    $user->payins()->save($payin);
    if (isset($params['property'])) {
      Property::where('reference', $params['property'])
          ->update([
            'landlord_id' => $user->id
      ]);
    }
  }

  protected function getFieldData($mapping, $key, $value) {
    if (!isset($mapping[$key])) {
      return null;
    }
    $res = $mapping[$key];
    $map = $mapping[$key];
    if (is_array($map)) {
      $data = explode(" ", trim($value));
      $res = [];
      for ($i = 0; $i < min(count($map), count($data)); $i++) {
        $res[$map[$i]] = $data[$i];
      }
      if (count($data) > count($map)) {
        $res[last(array_keys($res))].= ' '.join(" ", array_slice($data, count($map)));
      }
    }
    return $res;
  }

  protected function setMultipleFieldValue(&$row, $data) {
    foreach ($data as $key => $value) {
      $this->setFieldValue($row, $key, $value);
    }
  }

  protected function setFieldValue(&$row, $key, &$value) {
    $value = trim($value);
    if (isset($row[$key])) {
      $row[$key] .= " " . $value;
    }
    if (empty($value)) {
      $value = null;
    }
    if (in_array($key, $this->dateTimeAttributes) 
        && !empty(trim($value)) 
        && trim($value) != "") {
      $value = $this->parseDateTime($value);
    }
    if (!isset($row[$key]) 
        || !empty($value)) {
      $row[$key] = $value;
    }
  }
  
  protected function parseDateTime($value, $carbon = false) {
    $yearOffset = "";
    if (\DateTime::createFromFormat('Y-m-d', $value) !== false) {
      $format = 'd-m-Y';
      $year = explode("-", $value)[0];
      if (strlen($year) < 4) {
        $format = 'd-m-y';
      } 
    }else if (strpos($value, "/") > 0) {
      $year = explode("/", $value)[2];
      $format = 'd/m/y';
      if (strlen($year) == 4) {
        $format = 'd/m/Y';
      } else {
        $yearOffset = "20";
      }
    } else {
      $year = explode("-", $value)[2];
      $format = 'd-m-y';
      if (strlen($year) == 4) {
        $format = 'd-m-Y';
      } else {
        $yearOffset = "20";
      }
    }
    if ($carbon) {
      $value = Carbon::createFromFormat($format, $value);
    } else {
      $temp = Carbon::createFromFormat($format, $value)->format('Y-m-d');
      if (strlen($temp) != 10) {
        $temp = $yearOffset.$temp;
      }
      $value = $temp;
    }
    return $value;
  }

}
