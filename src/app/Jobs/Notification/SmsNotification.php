<?php

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    protected $nos;
    protected $message;
    protected $parameters;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile, $message, $parameters)
    {   
        $this->nos = $mobile;
        $this->message = $message;
        $this->parameters = $parameters;
        if (!is_array($this->nos)) {
          $this->nos = [$this->nos];
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      try {
        if (config('services.twilio.enabled') == 0) return;
          $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
          foreach ($this->nos as $no) {
            $message = $client->messages->create(
              $no,
              array(
                'from' => config('services.twilio.from'), // From a valid Twilio number
                'body' => $this->message
              )
            );
            Log::info(sprintf("Send SmsNotification with message sid %s to %s with message %s .", $message->sid, $no, $this->message));
          }
      } catch(Exception $e) {
          Log::Error('Error SmsNotification '. $e->getMessage());
      }
    }
}
