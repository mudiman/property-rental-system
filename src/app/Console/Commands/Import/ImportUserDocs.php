<?php

namespace App\Console\Commands\Import;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Document;

class ImportUserDocs extends ImportLandlord {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:user-docs {id?}';
  protected $currentDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import user docs';

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
    $this->info(sprintf("ImportUserDocs between %s", $this->currentDateTime->toDateTimeString()));

    $this->import();
  }

  protected function import() {

    $filesInFolder = File::allFiles(storage_path('smoor-import-assets').'/ids');

    foreach($filesInFolder as $file)
    {
      $filename = $file->getFilename();
      $filenameExploded = explode(' ', str_replace('.'.$file->getExtension(), '', $filename));
      $firstname = $filenameExploded[0];
      if (isset($filenameExploded[1])) {
        $lastname = $filenameExploded[1];
      }
      $query = User::where('first_name', 'like', "%$firstname%");
      if (isset($lastname)) {
        $query->where('last_name','like', "%$lastname%");
      }
      $user = $query->first();
      
      if (!empty($user)) {
        $documetFileName = str_replace(' ', '-', $filename);
        $document = Document::firstOrNew([
          'name' => 'Verification Document',
          'type' => 'passport',
          'filename' => $documetFileName,
          'bucket_name' => config('filesystems.disks.s3_document.bucket'),
          'mimetype' => mime_content_type($file->getRealPath()),
          'documentable_id' => $user->id,
          'documentable_type' => User::morphClass,
        ]);
        if (!$document->exists) {
          $this->info(sprintf("Uploading document for user id %s filename %s ", $user->id, $documetFileName));
          Storage::disk('s3_document')->put($documetFileName, file_get_contents($file->getRealPath()));
          $path = Storage::disk('s3_document')->url($documetFileName);
          $document->path = $path;
          $document->save();
        }
      }
    }
  }

}
