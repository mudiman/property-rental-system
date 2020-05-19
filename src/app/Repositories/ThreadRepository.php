<?php

namespace App\Repositories;

use App\Models\Thread;
use App\Models\Participant;

class ThreadRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'status',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Thread::class;
    }
    
    
    public function participantsThread($participantIds, $newParticipantIds = null)
    {
       // remove empty ids
      $participantIds = array_filter($participantIds);
      if ($newParticipantIds) {
        $newParticipantIds = array_filter($newParticipantIds);
      }
      // find existing thread with given particpants lists
      $threadId = Participant::select('thread_id')
          ->whereIn('user_id', $participantIds)
          ->groupBy('thread_id')
          ->havingRaw('count(user_id) = '. count($participantIds))
          ->get()
          ->pluck('thread_id');
      
      // filter out thread with only these particpants
      $threadWithOnlyTheseParticipants = null;
      if (!empty($threadId)) {
        // if multiple get first thread
        $threadWithOnlyTheseParticipants = Participant::select('thread_id')
          ->whereIn('thread_id', $threadId->all())
          ->groupBy('thread_id')
          ->havingRaw('count(user_id) = '. count($participantIds))
          ->first();
      }
      $paramThreadId = null;
      if (empty($threadId) || !$threadWithOnlyTheseParticipants) {
        $thread = Thread::create(['title' => 'thread'.implode("", $participantIds)]);
        foreach ($participantIds as $user_id) {
          if (empty($user_id)) continue;
          Participant::updateOrCreate(
              ['thread_id' => $thread->id, 'user_id' => $user_id]
              ,[]);
        }
        $paramThreadId = $thread->id;
      } else {
        $paramThreadId = $threadWithOnlyTheseParticipants->thread_id;
      }
      if (!empty($newParticipantIds)) {
        foreach ($newParticipantIds as $user_id) {
          if (empty($user_id)) continue;
          Participant::updateOrCreate(['thread_id' => $paramThreadId, 'user_id' => $user_id],[]);
        }
      }
      return $paramThreadId;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
