<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'title',
        'description',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Feedback::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
