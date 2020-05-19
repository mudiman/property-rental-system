<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository extends ParentRepository {

  /**
   * @var array
   */
  protected $fieldSearchable = [
    'path',
    'bucket_name',
    'filename',
    'type',
    'mimetype',
    'primary',
    'position',
    'imageable_id',
    'imageable_type',
    'updated_by',
    'created_by',
    'deleted_by'
  ];

  /**
   * Configure the Model
   * */
  public function model() {
    return Image::class;
  }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $model->created_by,
        'fromUserId' => config('business.admin.id'),
      ] + $model->toArray()))->notify();
  }

}
