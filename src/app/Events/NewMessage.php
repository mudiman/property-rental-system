<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessage  implements ShouldBroadcast
{
    use SerializesModels;
    
    public $broadcastQueue = 'message';

    public $message;
    public $room_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $room_id)
    {
        // Get message
        $this->message = $message;
        $this->room_id = $room_id;
        $this->broadcastQueue = config("queue.message");
    }
    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'.$this->room_id);
    }
}