<?php

namespace App\Repositories;

use App\Models\Participant;

class ParticipantRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'thread_id',
        'user_id',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Participant::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
