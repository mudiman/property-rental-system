<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\Notification\EmailNotification;
use App\Models\User;

class SendEmail implements ShouldQueue
{

  use InteractsWithQueue,
      Queueable,
      SerializesModels,
      DispatchesJobs;

  protected $templateName;
  protected $by_user;
  protected $for_user;
  protected $subject;
  protected $templateContent;
  protected $globalMergeData;
  protected $body;
  protected $parameters;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($by_user, $for_user, $subject, $body = null, $templateContent = array(), $templateName = null, $globalMergeData = array(), $parameters = array()) {
    $this->by_user = $by_user;
    $this->for_user = $for_user;
    $this->templateContent = $templateContent;
    $this->subject = $subject;
    $this->templateName = $templateName;
    $this->body = $body;
    $this->globalMergeData = $globalMergeData;
    $this->parameters = $parameters;
    if (!is_array($this->for_user)) {
      $this->for_user = [$this->for_user];
    }
  }

  /**
   * Execute the job.
   * @return void
   */
  public function handle() {
    $users = User::whereIn('id', $this->for_user)
        ->get();
    
    $messageType = isset($this->parameters['messageType']) ? $this->parameters['messageType']:null;
    foreach ($users as $user) {
      try {
        if (!$user->messageSettingEnabled("email_notification", $messageType)) continue;
        $this->dispatch(
            (new EmailNotification(
                $user->first_name, 
                $user->email, 
                $this->subject, 
                $this->body, 
                $this->templateContent, 
                $this->templateName, 
                $this->globalMergeData
            ))->onQueue(config('queue.email'))
        );
        Log::info(sprintf("Send email to %s with subject %s and template %s content %s.", json_encode($this->for_user), $this->subject, $this->body, json_encode($this->templateContent)));
      } catch (Exception $e) {
        Log::Error('Error Sending email ' . $e->getMessage());
      }
    }
  }
}
