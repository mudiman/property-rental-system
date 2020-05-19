<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Payin;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;
use Geocoder\Provider\GoogleMaps\Model\GoogleAddress;

class ImportAddressCoordinates extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:geocode';
  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import address coordinates';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }
  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->geocodeAddress();
  }
  protected function geocodeAddress() {
      $properties = Property::whereNull('cordinate')
              ->whereNotNull('postcode')
          ->get();
      $this->info(sprintf("Found %s", sizeof($properties)));
      foreach ($properties as $property) {
        $cordinates = app('geocoder')->geocode($property->postcode . ' ' . $property->county)->get();
        if ($cordinates->isNotEmpty()) {
            $this->info(sprintf("Updating cordinate for property %s", $property->id));
            $address = $cordinates->first();
            $this->info(sprintf("Found Address %s", var_dump($address->getLocality())));
            $cordinate = $address->getCoordinates();
            $lat = $cordinate->getLatitude();
            $lng = $cordinate->getLongitude();
            $property->cordinate = "$lat,$lng";
            $property->city = $address->getLocality();
            $property->country = $address->getCountry();
            $property->save();
        }
      }
      
  }
  

}
