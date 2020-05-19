<?php

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class EmailNotification implements ShouldQueue {

  use InteractsWithQueue,
      Queueable,
      SerializesModels;

  protected $templateName;
  protected $email;
  protected $to;
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
  public function __construct($to, $email, $subject, $body = null, $templateContent = array(), $templateName = null, $globalMergeData = array(), $parameters = array()) {
    $this->to = $to;
    $this->email = $email;
    $this->templateContent = $templateContent;
    $this->subject = $subject;
    $this->templateName = $templateName;
    $this->body = $body;
    $this->globalMergeData = $globalMergeData;
    $this->parameters = $parameters;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle() {
    try {
      $to = $this->to;
      $email = $this->email;
      if (!config('mail.enabled')) return;
      if (View::exists('email.' . $this->subject)) {
        $subject = View::make('email.' . $this->subject, $this->templateContent);
      } else {
        $subject = $this->subject;
      }

      if (View::exists('email.' . $this->body)) {
        Mail::send('email.' . $this->body, $this->templateContent, function ($message) use ($to, $email, $subject) {
          $message->from(Config('mail.from.address'), 'Smoor');
          $message->to($email, $to);
          $message->subject($subject);
        });
      } else {
        Mail::raw($this->body, function ($message) use ($to, $email, $subject) {
          $message->from(Config('mail.from.address'), 'Smoor');
          $message->to($email, $to);
          $message->subject($subject);
        });
      }

      Log::info(sprintf("Send email to %s with subject %s and template %s content %s.", $this->to, $this->subject, $this->body, \GuzzleHttp\json_encode($this->templateContent)));
    } catch (Exception $e) {
      Log::Error('Error Sending email ' . $e->getMessage());
    }
  }

}
