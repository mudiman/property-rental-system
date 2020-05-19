<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Thread;
use App\Repositories\MessageRepository;
use App\Repositories\ThreadRepository;


class SaveMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    protected $by_user;
    protected $thread;
    protected $for_user;
    protected $title;
    protected $message;
    protected $messageable_id;
    protected $messageable_type;
    protected $snapshot;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($by_user, $for_user, $thread, $title, $message, $messageable_id = null, $messageable_type = null, $snapshot = null)
    {
        $this->by_user = $by_user;
        $this->for_user = $for_user;
        $this->thread = $thread;
        $this->title = $title;
        $this->message = $message;
        $this->messageable_id = $messageable_id;
        $this->messageable_type = $messageable_type;
        $this->snapshot = $snapshot;
        
        if (!is_array($this->for_user)) {
          $this->for_user = [$this->for_user];
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
        $participantIds = array_unique(array_merge($this->for_user, [$this->by_user]));
        asort($participantIds);
        // find or create threads with specified set of users
        if (!$this->thread) {
          $threadRepository = \App::make(ThreadRepository::class);
          $threadId = $threadRepository->participantsThread($participantIds, null);
          $newThread = Thread::findorfail($threadId);
          $this->thread = $newThread->id;
        }

        $messageRepository = \App::make(MessageRepository::class);
        $input = [
          'by_user' => $this->by_user,
          'thread_id' => $this->thread,
          'title' => $this->title,
          'message' => $this->message,
          'messageable_id' => $this->messageable_id,
          'messageable_type' => $this->messageable_type,
          'snapshot' => $this->snapshot,
        ];
        $messageRepository->create($input);
        Log::info(sprintf("Save messaging with message %s .", $this->message));
      } catch (Exception $e) {
        Log::Error('Error Saving message ' . $e->getMessage());
      }
    }
}
