<?php

namespace App\Console\Commands\Import;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Property;
use App\Models\Image;

class ImportPropertyImages extends ImportLandlord {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:property-images {id?}';
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import property images';

  
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
    $this->info(sprintf("ImportPropertyImages between %s", $this->currentDateTime->toDateTimeString()));

    $this->import();
  }
  
  protected function import() {

    $filesInFolder = File::allFiles(storage_path('smoor-import-assets').'/property-images');

    foreach($filesInFolder as $file)
    {
      $propertyReference = basename($file->getPath());
      $property = Property::where('reference', $propertyReference)->first();
      if (!empty($property)) {
        $filename = $property->id.'-'.$file->getFilename();
        $image = Image::firstOrNew([
          'filename' => $filename,
          'mimetype' => mime_content_type($file->getRealPath()),
          'imageable_id' => $property->id,
          'imageable_type' => Property::morphClass,
        ]);
        if (!$image->exists) {
          $this->info(sprintf("Uploading image for property id %s filename %s ", $property->id, $filename));
          Storage::disk('s3_image')->put($filename, file_get_contents($file->getRealPath()));
          $path = Storage::disk('s3_image')->url($filename);
          $image->path = $path;
          $image->save();
        }
        if (empty($property->profile_picture)) {
          $property->profile_picture = $image->path;
          $property->save();
        }
      }
    }
  }
}
