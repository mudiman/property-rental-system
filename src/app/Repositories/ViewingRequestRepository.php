<?php

namespace App\Repositories;

use App\Models\ViewingRequest;

class ViewingRequestRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'viewing_id',
        'view_by_user',
        'check_in',
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
        return ViewingRequest::class;
    }
    
    
    public function dispatchNotification($config, $model, $from, $to)
    {
      $created_by_first_name = null;
      if ($model->created_by == $model->view_by_user) {
        $created_by_first_name = $model->viewByUser->first_name;
      } elseif($model->created_by == $model->viewing->conducted_by) {
        $created_by_first_name = $model->viewing->conductedBy->first_name;
      }
      
      $updated_by_first_name = $created_by_first_name;
      if (!empty($model->updated_by)) {
        if ($model->updated_by == $model->view_by_user) {
          $updated_by_first_name = $model->viewByUser->first_name;
        } elseif($model->updated_by == $model->viewing->conducted_by) {
          $updated_by_first_name = $model->viewing->conductedBy->first_name;
        }
      }
      
      (new \App\Support\Notification($config, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'tenant_first_name' => $model->viewByUser->first_name,
        'conductor_first_name' => $model->viewing->conductedBy->first_name,
        'created_by_first_name' => $created_by_first_name,
        'updated_by_first_name' => $updated_by_first_name,
        'viewing_start_date_time' => $model->viewing->start_datetime,
        'viewing_end_date_time' => $model->viewing->end_datetime,
        'property_title' => $model->viewing->property->title,
        'messageId' => $model->id,
        'messageType' => ViewingRequest::morphClass,
        'snapshot' => json_encode($model->toArray())
      ] ))->notify();
    }
}

