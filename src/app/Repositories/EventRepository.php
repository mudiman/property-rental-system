<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'user_id',
        'viewed',
        'eventable_id',
        'eventable_type',
        'start_datetime',
        'end_datetime',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Event::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
