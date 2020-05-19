<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Jobs\Notification\SmsNotification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\User;

class SendSms implements ShouldQueue {

  use InteractsWithQueue,
      Queueable,
      SerializesModels,
      DispatchesJobs;

  protected $by_user;
  protected $for_user;
  protected $people;
  protected $message;
  protected $parameters;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($by_user, $for_user, $message, $parameters) {
    $this->by_user = $by_user;
    $this->for_user = $for_user;
    $this->message = $message;
    $this->parameters = $parameters;
    if (!is_array($this->for_user)) {
      $this->for_user = [$this->for_user];
    }
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle() {
    $users = User::whereIn('id', $this->for_user)
        ->get();
    
    $messageType = isset($this->parameters['messageType']) ? $this->parameters['messageType']:null;
    
    foreach ($users as $user) {
      try {
        if (!$user->messageSettingEnabled("text_notification", $messageType)) continue;
        if (!isset($user->mobile) && !isset($user->phone)) continue;
        
        $nos = [];
        if (isset($user->mobile)) $nos[] = $user->mobile;
        if (isset($user->phone)) $nos[] = $user->phone;
        
        $this->dispatch(
            (new SmsNotification(
                $nos, 
                $this->message, 
                $this->parameters
            ))->onQueue(config('queue.sms'))
        );
        Log::info(sprintf("SendSms with message %s to %s .", json_encode($this->message), json_encode($nos)));
      } catch (Exception $e) {
        Log::Error('Error SendSms ' . $e->getMessage());
      }
    }
  }

}
