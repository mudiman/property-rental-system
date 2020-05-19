<?php

namespace App\Repositories;

use App\Models\Reference;

class ReferenceRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'by_user',
        'for_user',
        'comment',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Reference::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
