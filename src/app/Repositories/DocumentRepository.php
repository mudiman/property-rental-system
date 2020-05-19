<?php

namespace App\Repositories;

use App\Models\Document;

class DocumentRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'type',
        'mimetype',
        'issuing_country',
        'verified',
        'path',
        'bucket_name',
        'filename',
        'file_front_path',
        'file_front_filename',
        'file_front_mimetype',
        'file_back_path',
        'file_back_filename',
        'file_back_mimetype',
        'documentable_id',
        'documentable_type',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Document::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $model->created_by,
        'fromUserId' => config('business.admin.id'),
      ] + $model->toArray()))->notify();
  }

}
