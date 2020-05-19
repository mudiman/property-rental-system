<?php

namespace App\Repositories;

use App\Models\UserService;

class UserServiceRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'service_id',
        'pricing',
        'price',
        'extra_charges',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserService::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $model->user->id,
        'fromUserId' => config('business.admin.id'),
      ] ))->notify();
  }

}
