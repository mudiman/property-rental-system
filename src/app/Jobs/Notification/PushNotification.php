<?php

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class PushNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $deviceToken;
    protected $message;
    protected $alert;
    protected $sound;
    protected $type;
    protected $parameters;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deviceToken, $alert, $message, $type = 'message', $sound = "true", $parameters = array())
    {
        $this->deviceToken = $deviceToken;
        $this->alert = $alert;
        $this->message = $message;
        $this->type = $type;
        $this->sound = $sound;
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       try {
        if (config('services.onesignal.enabled') == 0) return;
        $sender = isset($this->parameters['sender']) ? $this->parameters['sender']: config('business.smoor.name');
        Log::info(sprintf("Send PushNotification with message %s. to device %s by sender %s and data %s.", $this->message, json_encode($this->deviceToken), $sender, json_encode($this->parameters)));
        
        $client = new Client();
        $condition = [
            'app_id'=> config('services.onesignal.api_key'),
            'include_player_ids'=> $this->deviceToken,
            'android_background_data'=>true,
            'data'=>[
                'alert' => $this->alert,
                'message'=> $this->message,
                'badge'=> 'Increment',
                'sound'=> $this->sound,
                'type'=> $this->type,
                'messageId'=> $this->parameters['messageId'],
                'messageType'=> $this->parameters['messageType'],
                'sender'=> $sender
            ],
            "contents"=> ["en"=> $this->message]
        ];
        
        if ($this->parameters['messageType'] == "message") {
          $condition['headings'] = ["en"=> $sender];
        }
        
        if (isset($this->parameters['title'])) {
          $condition['subtitle'] = ["en"=> $this->parameters['title']];
        }
        
        if (isset($this->parameters['related_id'])) {
          $condition['data']['related_id'] = $this->parameters['related_id'];
          $condition['data']['related_type'] = $this->parameters['related_type'];
        }
        
        $headers = [
          'Content-Type' => 'application/json; charset=utf-8',
          'Authorization'=> 'Basic '.config('services.onesignal.auth')
          ];

        $response  =  $client->request('POST', config('services.onesignal.url').'notifications', ['json'=>$condition, $headers]);
        Log::info(sprintf("Send PushNotification with message %s. Response %s.", $this->message, json_encode($response)));
      } catch(Exception $e) {
          Log::Error('Error PushNotification '. $e->getMessage());
      }
    }
}
