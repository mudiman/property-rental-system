<?php

namespace App\Repositories;

use App\Models\Viewing;

class ViewingRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'property_id',
        'conducted_by',
        'start_datetime',
        'end_datetime',
        'type',
        'status',
        'check_in',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Viewing::class;
    }
    
    
    public function dispatchNotification($config, $model, $from, $to, $delay = null)
    {
      (new \App\Support\Notification($config, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'from_user_first_name' => $model->conductedBy->first_name,
        'conductor_first_name' => $model->conductedBy->first_name,
        'viewing_start_date_time' => $model->start_datetime,
        'viewing_end_date_time' => $model->end_datetime,
        'property_title' => $model->property->title,
        'messageId' => $model->id,
        'messageType' => Viewing::morphClass,
        'delay' => $delay,
        'snapshot' => json_encode($model->toArray())
      ] ))->notify();
    }
}