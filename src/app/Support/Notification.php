<?php

namespace App\Support;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendEmail;
use App\Jobs\SendSms;
use App\Jobs\SaveMessage;
use App\Jobs\SendPushNotification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Jobs\Notification\SmsNotification;
use App\Jobs\Notification\PushNotification;
use App\Jobs\Notification\EmailNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class Notification 
{
  
  use DispatchesJobs;
  //put your code here
  
  public $message = null;
  protected $delay = 0;
  protected $parameters = array();
  protected $environment = 'live';
  
  public function __construct($event = null, $parameters = array()) {
    if ($event) {
      $this->message = config('messages.'.$event);
    }
    $this->parameters = $parameters;
    $user = Auth::user();
    $this->parameters['timezone'] = (isset($user) && isset($user->timezone)) ? Auth::user()->timezone: 'Europe/London';
    $this->delay = !isset($parameters['delay']) ? 0: $parameters['delay'];
    $this->environment = App::environment();
        
    Log::debug('notification',['event' => $event, 'message' => json_encode($this->message)]);
  }
  
  public function setMessage($message) {
    $this->message = $message;
  }
  
  private function makeView($type, $templateName)
  {
    if (View::exists($type.".".$templateName)) {
        $view = View::make($type.".".$templateName, $this->parameters);
        return $view->render();
    }
    return $templateName;
  }
  
  public function formatString($type)
  {
    $res = [];
    $debuginfo = '';
    if (in_array($this->environment,['local', 'dev']) 
            && isset($this->parameters['messageId'])
            && config('app.debug') == true) {
      $debuginfo = ' DebugId '.$this->parameters['messageId'];
    }
    switch ($type) {
      case 'in_app':
      case 'push':
        $res['title'] = $this->makeView($type, $this->message['title']);
        $res['description'] = $this->makeView($type, $this->message['description']).$debuginfo;
        break;
      case 'sms':
        $res['sms'] = $this->makeView($type, $this->message['smsTemplate']).$debuginfo;
        break;
      case 'email':
        $res['subject'] = $this->makeView($type, $this->message['subjectTemplate']);
        $res['body'] = $this->makeView($type, $this->message['bodyTemplate']).$debuginfo;
        break;
    }
    return $res;
  }
  
  public function notify()
  {
    if ($this->message) {
      foreach ($this->message['type'] as $type) {
        Log::info(sprintf("Sending on channel %s .", $type));
        switch ($type) {
          case 'in_app':
            $this->notifyInApp();
            break;
          case 'sms':
            $this->notifySms();
            break;
          case 'push':
            $this->notifyPushNotification();
            break;
          case 'email':
            $this->notifyEmail();
            break;
        }
      }
    }
  }
  
  public function notifySingle()
  {
    if ($this->message) {
      foreach ($this->message['type'] as $type) {
        switch ($type) {
          case 'in_app':
            $this->notifySingleInApp();
            break;
          case 'sms':
            $this->notifySingleSms();
            break;
          case 'push':
            $this->notifySinglePushNotification();
            break;
          case 'email':
            $this->notifySingleEmail();
            break;
        }
      }
    }
  }
  
  public function notifyInApp($parameters = [])
  {
    $combineParameters  = $parameters + $this->parameters;
    $thread = isset($this->parameters['thread']) ? $this->parameters['thread']:null;
    $res = $this->formatString('in_app');
    
    $this->dispatch(
        (new SaveMessage($this->parameters['fromUserId'], 
            $this->parameters['toUserId'], 
            $thread, 
            $res['title'], 
            $res['description'], 
            $combineParameters['messageId'], 
            $combineParameters['messageType'], 
            $combineParameters['snapshot']))
        ->onQueue(config('queue.notification'))->delay($this->delay)
    );
  }
  
  public function notifySms($parameters = [])
  {
    $combineParameters  = $parameters + $this->parameters;
    $res = $this->formatString('sms');
    
    $this->dispatch(
          (new SendSms(
            $combineParameters['fromUserId'],
            $combineParameters['toUserId'], 
            $res['sms'],
            $parameters + $this->parameters
          ))->onQueue(config('queue.notification'))->delay($this->delay)
        );
  }
  
  public function notifyEmail($parameters = [])
  {
    $this->dispatch(
      (new SendEmail(
        $this->parameters['fromUserId'], 
        $this->parameters['toUserId'], 
        $this->message['subjectTemplate'], 
        $this->message['bodyTemplate'],
        $parameters + $this->parameters,
        null,
        [],
        $parameters + $this->parameters
      ))->onQueue(config('queue.notification'))->delay($this->delay)
    );
  }
  
  public function notifyPushNotification($parameters = [])
  {
    $combineParameters  = $parameters + $this->parameters;
    $res = $this->formatString('push');
    
    $this->dispatch(
        (new SendPushNotification(
            $combineParameters['fromUserId'], 
            $combineParameters['toUserId'], 
            $this->message['alert'], 
            $res['description'], 
            $this->message['pushType'], 
            $this->message['sound'], 
            $combineParameters
        ))->onQueue(config('queue.notification'))->delay($this->delay)
    );
  }
  
  public function notifySingleSms($parameters = [])
  {
    $res = $this->formatString('sms');
    $this->dispatch(
          (new SmsNotification(
            $this->parameters['mobile'],
            $res['sms'],
            $parameters + $this->parameters
          ))->onQueue(config('queue.sms'))->delay($this->delay)
        );
  }
  
  public function notifySingleEmail($parameters = [])
  {
    $this->dispatch(
          (new EmailNotification(
            $this->parameters['first_name'], 
            $this->parameters['email'], 
            $this->message['subjectTemplate'], 
            $this->message['bodyTemplate'], 
            $parameters + $this->parameters
          ))->onQueue(config('queue.email'))->delay($this->delay)
        );
  }
  
  public function notifySinglePushNotification($parameters = [])
  {
    $res = $this->formatString('push');
    $this->dispatch(
        (new PushNotification(
            [$this->parameters['device_id']], 
            $this->message['alert'], 
            $res['description'], 
            $this->message['pushType'], 
            $this->message['sound'], 
            $parameters + $this->parameters
        ))->onQueue(config('queue.push'))->delay($this->delay)
    );
  }
}