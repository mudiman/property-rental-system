<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use App\Events\NewMessage;
use App\Models\Thread;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;
    
    protected $message_id;
    protected $event;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->message_id = $id;
            
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      try {
        $message = Message::with('byUser')->with("messageable")->find($this->message_id);
        if (empty($message)) return;
        
        $thread = Thread::with('participantUsers')->find($message->thread_id);
        $data = $message->toArray();
        
        foreach ($thread->participantUsers as $forUser) {
          if ($message->by_user != $forUser->id) {
            $data['for_user'] = $forUser;
            $data['for_user_id'] = $forUser->id;
            event(new NewMessage($data, $forUser->id));
            
            // only send push notification if message directly send to user
            if (empty($message->messageable_id)) { 
              $this->dispatch(
                (new SendPushNotification(
                    $message->by_user, 
                    $forUser->id, 
                    true, 
                    $message->message, 
                    'message',
                    true,
                    [
                      'messageId' => $message->id,
                      'messageType' => $message->morphClass,
                    ]
                ))->onQueue(config('queue.notification'))
              );
            }
          }
        }
        Log::info(sprintf("Send messaging with message id %s.", $this->message_id));
      } catch(Exception $e) {
          Log::Error('Error Sending message '. $e->getMessage());
      }
    }
}
