<?php

namespace App\Repositories;

use App\Models\Review;
use App\Models\User;
use App\Models\Property;

class ReviewRepository extends ParentRepository
{
    protected $score_type_id;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'by_user',
        'for_user',
        'comment',
        'score_type_id',
        'rating',
        'punctuality',
        'quality',
        'reviewable_id',
        'reviewable_type',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Review::class;
    }
    

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    $review = '';
    switch ($model->reviewable_type) {
      case User::morphClass:
        $review = 'you';
        break;
      case Property::morphClass:
        $review = "your property '".$model->reviewable->title."'";
        break;
      default:
        $review = 'your '.$model->reviewable_type;
        break;
    }
    
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'from_first_name' => $model->createdBy->first_name,
        'to_first_name' => $model->forUser->first_name,
        'review' => $review,
        'rating' => $model->rating,
        'punctuality' => $model->punctuality,
        'quality' => $model->quality,
        'reviewable_type' => $model->reviewable_type,
        'messageId' => $model->id,
        'messageType' => Review::morphClass,
        'snapshot' => json_encode($model->toArray())
      ]))->notify();
  }

}
