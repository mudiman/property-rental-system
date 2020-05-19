<?php

namespace App\Repositories;

use App\Models\Like;
use App\Models\Property;
use App\Models\User;

class LikeRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'likeable_id',
        'likeable_type',
        'user_id',
        'type',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Like::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    $like = '';
    switch ($model->likeable_type) {
      case User::morphClass:
        $like = 'you';
        break;
      case Property::morphClass:
        $like = "your property '".$model->likeable->title."'";
        break;
      default:
        $like = 'your '.$model->likeable_type;
        break;
    }
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'by_first_name' => $model->user->first_name,
        'like' => $like,
        'messageId' => $model->id,
        'messageType' => Like::morphClass,
        'likeable_id' => $model->likeable->id,
        'likeable_type' => $model->likeable->morphClass,
        'snapshot' => json_encode($model->toArray())
      ]))->notify();
  }

}
