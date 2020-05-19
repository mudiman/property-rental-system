<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Jobs\Notification\PushNotification;

class SendPushNotification implements ShouldQueue {

  use InteractsWithQueue,
      Queueable,
      SerializesModels,
      DispatchesJobs;

  protected $by_user;
  protected $for_user;
  protected $type;
  protected $sound;
  protected $message;
  protected $alert;
  protected $parameters;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($by_user, $for_user, $alert, $message, $type = 'message', $sound = "true", $parameters = array()) {
    $this->by_user = $by_user;
    $this->for_user = $for_user;
    $this->alert = $alert;
    $this->message = $message;
    $this->type = $type;
    $this->sound = $sound;
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
    
    $byUser = User::findorfail($this->by_user);
    $this->parameters['sender'] = $byUser->first_name;
    
    $users = User::whereIn('id', $this->for_user)
        ->with('devices')
        ->get();
    
    $messageType = isset($this->parameters['messageType']) ? $this->parameters['messageType']:null;
    
    foreach ($users as $user) {
      if (!$user->messageSettingEnabled("push_notification", $messageType)) continue;
      $device_ids = [];
      foreach ($user->devices as $device) {
        $device_ids[] = $device->device_id;
      }
      if(empty($device_ids)) continue;
      
      try {
        $this->dispatch(
            (new PushNotification($device_ids, 
                $this->alert, 
                $this->message, 
                $this->type, 
                $this->sound,
                $this->parameters
            ))->onQueue(config('queue.push'))
        );
        Log::info(sprintf("Send push notification with message %s. with param %s.", json_encode($this->message), json_encode($this->parameters)));
      } catch (Exception $e) {
        Log::Error('Error Sending push notification ' . $e->getMessage());
      }
    }
  }
}
