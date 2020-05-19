<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Property;

class ImportProperty extends ImportLandlord {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:property {file}';
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import property';

  protected $propertyMapping = [
    "property_ref" => "reference",
    "move_in_date" => null,
    "road_name" => null,
    "available_date" => "available_date",
    "letting_type" => "letting_type",
    "property_type" => "property_type",
    "door_number" => "door_number",
    "building_name" => null,
    "house_flat_number" => "door_number",
    "street_name" => "street",
    "county" => "county",
    "city" => "city",
    "postcode" => "postcode",
    "floor_number" => "floor",
    "number_of_floors" => "floors",
    "apartment_floor" => "floor",
    "letting_type" => "letting_type",
    "number_of_bedrooms" => "bedrooms",
    "number_of_receptions" => "receptions",
    "number_of_bathrooms" => "bathrooms",
    "ensuite" => "ensuite",
    "garden" => "has_garden",
    "balconyterrace" => "has_balcony_terrace",
    "parking" => "has_parking",
    "title" => "title",
    "summary" => "summary",
    "monthly_rental" => "rent_per_month",
    "monthly_fee" => null,
    "pro_fee" => "3%",
    "whats_included" => "inclusive",
    "area_overview" => "area_overview",
    "nearest_station" => "getting_around",
    "house_rules" => "rules",
    "amount_to_suspense" => null,
    "amount_to_property_pro" => null,
    "property_pro" => null,
  ];
  
  protected $dateTimeAttributes = [
    "move_in_date",
    "available_date"
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
    $this->info(sprintf("ImportProperty between %s", $this->currentDateTime->toDateTimeString()));

    $this->import();
  }
  
  protected function import() {
    $data = Excel::load(storage_path('csv').'/'.$this->argument('file').'.csv', function($results) {})->get();

    if (!empty($data) && $data->count()) {
      foreach ($data as $row) {
        $record = $this->parseData($row, $this->propertyMapping);
        if (!empty($record)) {
          $this->insertAndUpdate($record, []);
        }
      }
    }
  }
  
  protected function insertAndUpdate($record, $params) {
    $record += [
      'landlord_id' => 1,
      'status' => Property::STATUS_LIVE,
      "country" => "United Kingdom",
    ];
    $record['rent_per_month'] = number_format(floatVal(str_replace(",", "", $record['rent_per_month'])), 2, '.', '');
    $property = Property::updateOrCreate(['reference' => $record['reference']], $record);
    $property->status = Property::STATUS_LIVE;
    $property->save();
  }

}
