<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Models\Payin;
use App\Models\User;
use App\Models\Document;
use App\Support\Helper;
use App\Repositories\PayinRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class VerifyAccountWithIdentity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $payinId;
    private $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $payinId)
    {
        $this->userId = $userId;
        $this->payinId = $payinId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $payin = Payin::findorfail($this->payinId);
       
       $document = Document::where('documentable_id', $this->userId)
            ->where('documentable_type', User::morphClass)
            ->whereIn('type', [Document::TYPE_IDENTITY_DOCUMENT, Document::TYPE_PASSPORT, Document::TYPE_DRIVING_LICENSE])
            ->first();
        
        if (empty($document)) {
          Log::info(sprintf("No verifiable document for this user %s .", $this->userId));
          return;
        }
        if (!empty($document->bucket_name)) {
          Config::set('filesystems.disks.s3_document.bucket', $document->bucket_name);
        }
        $filename = null;
        if (isset($document->filename)) {
          $filename = $document->filename;
        } elseif (isset($document->file_front_filename)) {
          $filename = $document->file_front_filename;
        } elseif (isset($document->file_back_filename)) {
          $filename = $document->file_back_filename;
        }
        if (!$filename) {
          Log::info(sprintf("No file found for %s .", $this->userId));
          return;
        }
        $temp = Helper::temporaryFile(uniqid(), Storage::cloud('s3_document')->get($filename));
        $fp = fopen($temp, 'r');
        $stripeFile = \Stripe\FileUpload::create(array(
          'purpose' => 'identity_document',
          'file' => $fp
        ));
        $payin->payment_gateway_identity_document = json_encode($stripeFile);
        $payin->payment_gateway_identity_document_id = $stripeFile->id;
        Payin::$FIRE_EVENTS = false;
        $payin->save();
        
        $payinRepo = \App::make(PayinRepository::class);
        $payinRepo->updateSubMerchantAccount($payin);
    }
}
