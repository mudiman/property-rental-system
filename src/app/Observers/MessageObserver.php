<?php

namespace App\Observers;

use App\Models\Message;
use App\Repositories\MessageRepository;
use Carbon\Carbon;

class MessageObserver extends ParentObserver {

  protected $imageRepository;

  public function __construct() {
    parent::__construct();
    $this->messageRepository = \App::make(MessageRepository::class);
  }
  
  public function created(Message $model) {
    $this->sendMessage($model);
    
    $thread = $model->thread;
    $thread->updated_at = Carbon::now();
    $thread->save();
  }
  

}
